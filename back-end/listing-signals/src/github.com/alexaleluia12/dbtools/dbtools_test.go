package dbtools

import (
	"database/sql"
	"reflect"
	"testing"
)

type ForMany struct {
	value  string
	result [][]string
}

var lst = []ForMany{
	{"select * from `empresa` limit 3",
		[][]string{{"1", "ABEV3"}, {"2", "ALPA4"}, {"3", "ALSC3"}}},
}
var pathConf = "/home/alex/Documentos/cod/my/go-rsi-project/config-rsi/banco.json"

func TestMany(t *testing.T) {
	confClear, err := BancoConfig(pathConf)
	if err != nil {
		t.Error("pretest", err.Error())
	}

	db, err := sql.Open("mysql", confClear)

	if err != nil {
		t.Error("preetest", err.Error())
	}
	defer db.Close()

	for _, v := range lst {
		resp := Many(db, v.value)
		if !reflect.DeepEqual(resp, v.result) {
			t.Error(
				"From", v.value,
				"expected", v.result,
				"got", resp,
			)
		}
	}
}
