package main

import (
	"database/sql"
	"fmt"
	"strconv"
	"time"

	"github.com/alexaleluia12/dbtools"
	"github.com/alexaleluia12/serietools"
	"github.com/alexaleluia12/utils"
)

const pathConf = "./config-auto/banco.json"

type WriteRSIListing struct {
	Owner int
	Type  string
}

func listingOnline() {

	agora := time.Now()
	agoraFull := agora.Format(utils.FormatDateTime)
	confClear, err := dbtools.BancoConfig(pathConf)
	if err != nil {
		fmt.Println(agoraFull, "Ler configuracao de banco", err.Error())
	}

	db, err := sql.Open("mysql", confClear)

	if err != nil {
		fmt.Println(agoraFull, "Conectar ao banco", err.Error())
	}
	defer db.Close()

	var lstWrite []WriteRSIListing
	queryLastDate := "SELECT `valor`.`date` FROM `valor` ORDER by `valor`.`date` DESC LIMIT 1"
	lastDate := dbtools.One(db, queryLastDate)
	endDate := lastDate[0]
	// posso ate diminuir as datas so me interasa o ultimo valor do rsi
	tmpEndDate, _ := time.Parse(utils.FormatDate, endDate)
	tmpStart := tmpEndDate.AddDate(0, -2, 0)
	startDate := tmpStart.Format(utils.FormatDate)
	// startDate := tmpStart.Format(utils.FormatDate)
	dateStartDB := tmpStart.AddDate(0, -6, 0).Format(utils.FormatDate)

	queryEmpresaID := "SELECT `validos`.`id` from validos"
	templateQuery := "SELECT `valor`.`close`, `valor`.`date` FROM `valor` WHERE `valor`.`owner`= %s AND `valor`.`date` >= '%s' ORDER BY `valor`.`date` ASC"
	templateQueryRSI := "SELECT * FROM `rsiconf` WHERE `rsiconf`.`owner` = %s"
	lenRsiConf := 6

	lstEmpresas := dbtools.Many(db, queryEmpresaID)

	max := len(lstEmpresas)
	for i := 0; i < max; i++ {
		idEmpresa := lstEmpresas[i][0]
		query := fmt.Sprintf(templateQuery, idEmpresa, dateStartDB)
		serie := serietools.NewSeriet(db, query)
		j1, ok1 := serie.PointData(startDate)

		j2, ok2 := serie.PointData(endDate)

		if ok1 && ok2 && j1 > 0 {
			tmpQuery := fmt.Sprintf(templateQueryRSI, idEmpresa)
			rsiDB := dbtools.One(db, tmpQuery)

			if len(rsiDB) != lenRsiConf {
				fmt.Println(agoraFull, "nao foi possivel procesasr a listagem para (nao possi configuracao de rsi)", idEmpresa)
				continue
			}
			intSeason, _ := strconv.Atoi(rsiDB[3])
			Buy, _ := strconv.ParseFloat(rsiDB[5], 64)
			Sell, _ := strconv.ParseFloat(rsiDB[4], 64)
			rsi := serie.RSI(j1, j2, intSeason)

			lastRsiValue := rsi[len(rsi)-1]
			iid, _ := strconv.Atoi(idEmpresa)
			if lastRsiValue <= Buy {
				tmpWrite := WriteRSIListing{Owner: iid, Type: "B"}
				lstWrite = append(lstWrite, tmpWrite)
			} else if lastRsiValue >= Sell {
				tmpWrite := WriteRSIListing{Owner: iid, Type: "S"}
				lstWrite = append(lstWrite, tmpWrite)
			}

		} else {
			fmt.Println(agoraFull, "nao foi processado o rsi para (indices invalidos)", idEmpresa)
		}
	}

	// write on database
	// id, owner, date_set, season, sell, buy
	queryDelete := "DELETE FROM `listing`"
	queryInsert := "INSERT INTO `listing` VALUES (?, ?)"
	if len(lstWrite) > 0 {
		preInsert, err := db.Prepare(queryInsert)
		if err != nil {
			panic(err.Error())
		}
		defer preInsert.Close()

		_, err = db.Exec(queryDelete)
		if err != nil {
			fmt.Println(agoraFull, "falha ao excluir listing")
		}

		countSucess := 0
		countFail := 0
		for _, v := range lstWrite {
			_, err := preInsert.Exec(v.Owner, v.Type)
			if err != nil {
				countFail++
			} else {
				countSucess++
			}
		}
		fmt.Println(agoraFull, "Sucesso insercao: ", countSucess)
		fmt.Println(agoraFull, "Falha insercao: ", countFail)
	} else {
		fmt.Println(agoraFull, "Nenhum sinal")
	}
}

/*
TODO
- apaga tudo de listing
- pega o ultimo preco de todas as acoes
  configuracaos do ris para aquela acao
  calculo do rsi para aquela acao

  se valor_rsi <= compra
    insert into listing (id_acao, 'B')
  else if valor_rsi >= venda
    insert into listing (id_acao, 'S')


-- tabela --
CREATE TABLE `listing` (
  `owner` BIGINT NOT NULL,
  `type` CHAR(1) DEFAULT '0', -- B / S
  FOREIGN KEY(`owner`) REFERENCES `empresa`(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
func main() {

	listingOnline()
}
