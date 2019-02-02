package utils

import (
	"fmt"
	"strconv"
	"strings"
)

// FormatDateTime yyyy-mm-dd hh:ii:ss
const FormatDateTime string = "2006-01-02 15:04:05"

// FormatDate yyyy-mm-dd
const FormatDate string = "2006-01-02"

// InArrayStr string
func InArrayStr(alvo string, lst []string) bool {
	for _, valor := range lst {
		if valor == alvo {
			return true
		}
	}

	return false
}

// InArrayInt int
func InArrayInt(alvo int, lst []int) bool {
	for _, valor := range lst {
		if valor == alvo {
			return true
		}
	}

	return false
}

// String2Interface convert array strigo to interface{} array
func String2Interface(lst []string) []interface{} {
	lsti := make([]interface{}, len(lst))
	for i, v := range lst {
		lsti[i] = v
	}

	return lsti
}

// ConverData de 6/2/2017 para 2017-06-02
func ConverData(entrada string) string {
	partes := strings.Split(entrada, "/")
	var saida string
	template := "%02d"
	mes, err := strconv.Atoi(partes[0])
	if err != nil {
		panic(err.Error())
	}

	dia, err := strconv.Atoi(partes[1])
	if err != nil {
		panic(err.Error())
	}

	ano, err := strconv.Atoi(partes[2])
	if err != nil {
		panic(err.Error())
	}

	// fmt.Println(ano)
	saida = strconv.Itoa(ano) + "-" + fmt.Sprintf(template, mes) + "-" + fmt.Sprintf(template, dia)

	return saida
}
