package serietools

import (
	"database/sql"
	"fmt"
	"sort"
	"strconv"

	"github.com/alexaleluia12/dbtools"
)

const BUY = 'B'
const SOLD = 'S'
const MONEY = 10000.0
const BROKAGE = 0.5 / 100.0

// Point represent a point e time serie
type Point struct {
	price float64
	date  string
}

// Seriet the all temporal serie
type Seriet struct {
	serie []Point
}

type Signal struct {
	// B or S
	Stype byte
	// index on Seriet
	Sindex int
}

// NewSeriet init the Seriet object, db result data need be crescente.
// first price than date
func NewSeriet(db *sql.DB, query string) *Seriet {
	s := new(Seriet)
	var anterior string
	serieBanco := dbtools.Many(db, query)
	for i, v := range serieBanco {
		vpreco, err := strconv.ParseFloat(v[0], 64)
		if err != nil {
			fmt.Println("Cant convert to float,", err.Error())
			return nil
		}

		vdata := v[1]
		var tmpPoint = Point{vpreco, vdata}
		if i != 0 {
			if !(vdata > anterior) {
				fmt.Println("Fail construct the serie time need to be creacente")
				return nil
			}
		}
		anterior = vdata
		s.serie = append(s.serie, tmpPoint)
	}

	return s
}

// Price return price
func (p *Point) Price() float64 {
	return p.price
}

// Date return date
func (p *Point) Date() string {
	return p.date
}

// Len return the serie size
func (s *Seriet) Len() int {
	return len(s.serie)
}

// At return the point at index i
func (s *Seriet) At(i int) Point {
	return s.serie[i]
}

// PointData return the index of first >= ocorrence of value,
// false when not found
func (s *Seriet) PointData(value string) (int, bool) {
	resp := sort.Search(s.Len(), func(index int) bool {
		return s.serie[index].date >= value
	})

	if resp == -1 || resp == s.Len() {
		return 0, false
	}
	return resp, true

}

// Print show the serie at stdout
func (s *Seriet) Print() {
	fmt.Println(s.serie)
}

// RSI return the rsi for star, end point in time serie
func (s *Seriet) RSI(start int, end int, season int) []float64 {
	var out []float64
	var left, rigth int
	var sumWin float64
	var sumLost float64
	var current, old, averageWin, averageLost, rsiValue float64
	divider := float64(season)

	minimum := (start - season) >= 0
	if !minimum {
		a := fmt.Sprintf(" start - season: %d; start=%d, season=%d", (start - season), start, season)
		panic("the serie length is not enougth" + a)
	}
	left = start - season
	rigth = start + 1
	max := end - start + 1

	for x := 0; x < max; x++ {
		sumLost = 0.0
		sumWin = 0.0

		for j := left; j < rigth; j++ {

			if j == left {
				continue
			}
			// fmt.Println("oioii")
			//fmt.Println("j", j, "left", left, "right", rigth, "x", x)
			current = s.serie[j].price
			old = s.serie[j-1].price

			if current > old {
				sumWin += current - old
			} else {
				sumLost += -1.0 * (current - old)
			}
		}

		averageLost = sumLost / divider
		averageWin = sumWin / divider
		// fmt.Println(sumLost, sumWin)
		rsiValue = 100.0 * (averageWin / (averageWin + averageLost))

		rigth++
		left++
		out = append(out, rsiValue)
	}

	return out
}

// Signals return sintal list for rsi with buy, sold limit (no restriction)
func (s *Seriet) Signals(start, end int, rsiList []float64, buy, sold int64) []Signal {
	var lstOutSingal []Signal
	var j = 0
	var wasbuy, wassold bool
	wasbuy = false
	wassold = false
	for i := start; i <= end; i++ {
		vrsi := int64(rsiList[j])
		if vrsi < buy && !wasbuy {
			wasbuy = true
		} else if vrsi >= buy && wasbuy {
			wasbuy = false
			newSignal := Signal{Sindex: i, Stype: BUY}
			lstOutSingal = append(lstOutSingal, newSignal)
		}

		if vrsi > sold && !wassold {
			newSignal := Signal{Sindex: i, Stype: SOLD}
			lstOutSingal = append(lstOutSingal, newSignal)
			wassold = true
		} else if vrsi <= sold && wassold {
			wassold = false
		}

		j++
	}

	return lstOutSingal
}

func Amount(capital, price float64) int {
	return int((capital / (BROKAGE + 1)) * (1 / price))
}

// Process return the profit from lst buy/sell signals
func (s *Seriet) Process(lst []Signal) float64 {

	if len(lst) == 0 {
		return 0.0
	}

	var wassold = false
	var wasbuy = false
	var start = true

	// definir dinheiro + corretagem
	var scapital float64 = MONEY
	var lastAmount = 0
	for _, v := range lst {
		sprice := s.serie[v.Sindex].price
		if start {
			if v.Stype == BUY {
				qnt := Amount(scapital, sprice)
				lastAmount = qnt
				scapital -= (float64(qnt) * sprice) * (BROKAGE + 1.0)
				start = false
				wasbuy = true
			}
		} else {
			// make sold operate free the buy
			if v.Stype == SOLD && !wassold {
				scapital += (float64(lastAmount) * sprice) * (1 - BROKAGE)
				wassold = true
				wasbuy = false
			} else if v.Stype == BUY && !wasbuy {
				qnt := Amount(scapital, sprice)
				lastAmount = qnt
				scapital -= (float64(qnt) * sprice) * (BROKAGE + 1.0)
				wasbuy = true
				wassold = false
			}
		}
	}
	// last singal need be a sold, if not it is forced
	if lst[len(lst)-1].Stype != SOLD {
		// log the help if is only using buy bold
		sprice := s.serie[lst[len(lst)-1].Sindex].price
		scapital += (float64(lastAmount) * sprice) * (1 - BROKAGE)
	}

	return ((scapital - MONEY) / MONEY) * 100.0
}
