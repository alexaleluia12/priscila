package serietools

import (
	"database/sql"
	"strings"
	"testing"

	"github.com/alexaleluia12/dbtools"
)

type ForInitSerieT struct {
	query  string
	result int
}

var lst = []ForInitSerieT{
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`" +
		" = 1 ORDER BY `valor`.`date` ASC LIMIT 300", 300},
}
var pathConf = "/home/alex/Documentos/cod/my/go-rsi-project/config-rsi/banco.json"

func TestNew(t *testing.T) {
	confClear, err := dbtools.BancoConfig(pathConf)
	if err != nil {
		t.Error("pretest", err.Error())
	}

	db, err := sql.Open("mysql", confClear)

	if err != nil {
		t.Error("preetest", err.Error())
	}
	defer db.Close()

	for _, v := range lst {
		resp := NewSeriet(db, v.query)
		if resp.Len() != v.result {
			t.Error(
				"From", v.query,
				"expected", v.result,
				"got", resp.Len(),
			)
		}
	}
}

type ForPointData struct {
	query  string
	date   string
	index  int
	answer bool
}

var lstPointData = []ForPointData{
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= 1 ORDER BY `valor`.`date` ASC LIMIT 45", "2015-01-12", 6, true},
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= 1 ORDER BY `valor`.`date` ASC LIMIT 45", "2010-03-10", 0, true},
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= 1 ORDER BY `valor`.`date` ASC LIMIT 45", "2015-03-10", 0, false},
}

func TestPintData(t *testing.T) {
	confClear, _ := dbtools.BancoConfig(pathConf)
	db, _ := sql.Open("mysql", confClear)
	defer db.Close()

	for _, v := range lstPointData {
		temporalSerie := NewSeriet(db, v.query)
		resp, ok := temporalSerie.PointData(v.date)
		if ok != v.answer {
			t.Error(
				"From", v.query,
				"expected", v.answer,
				"got", ok,
			)
		}

		if resp != v.index {
			t.Error(
				"From", v.query,
				"expected", v.index,
				"got", resp,
			)
		}
	}
}

type ForRSI struct {
	query      string
	dateStart  string
	dateEnd    string
	seasonSize int
	ansLen     int
	ansLast    float64
}

var lstRSI = []ForRSI{
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= 3 ORDER BY `valor`.`date` ASC LIMIT 500 ", "2015-02-02", "2017-01-03", 15, 476, 54.09},
	{"SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= 3 ORDER BY `valor`.`date` ASC LIMIT 500 ", "2015-02-02", "2017-01-03", 26, 0, 0.0},
}

func TestRSI(t *testing.T) {
	confClear, _ := dbtools.BancoConfig(pathConf)

	db, _ := sql.Open("mysql", confClear)
	defer db.Close()

	defer func() {
		var strValue = recover().(string)
		// -5 first index is at 21, season(26) - start = -5
		// whitch can't generate time serie
		if !(strings.Contains(strValue, "-5")) {
			t.Error(
				"expected", "-5",
				"got", strValue,
			)
		}
	}()
	for _, v := range lstRSI {
		temporalSerie := NewSeriet(db, v.query)
		i1, _ := temporalSerie.PointData(v.dateStart)
		i2, _ := temporalSerie.PointData(v.dateEnd)
		lstAns := temporalSerie.RSI(i1, i2, v.seasonSize)
		if len(lstAns) != v.ansLen {
			t.Error(
				"expected", v.ansLen,
				"got", len(lstAns),
			)
		}

		if !(lstAns[len(lstAns)-1]-v.ansLast > 0.001) {
			t.Error(
				"expected ", (lstAns[len(lstAns)-1]-v.ansLast > 0.001),
				"be false",
			)
		}
	}

}
