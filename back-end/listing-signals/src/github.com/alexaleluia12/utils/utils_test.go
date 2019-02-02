package utils

import "testing"

type ForArrStrBool struct {
	values []string
	key    string
	result bool
}

var lstInArrayStr = []ForArrStrBool{
	{[]string{"name", "world", "Carolina", "blue", "Rick"}, "Carolina", true},
	{[]string{"some", "where", "there", "is", ":)"}, "nothing", false},
}

func TestInArrayStr(t *testing.T) {
	for _, v := range lstInArrayStr {
		resp := InArrayStr(v.key, v.values)
		if resp != v.result {
			t.Error(
				"From", v.key, v.values,
				"\nexpected", v.result,
				"got", resp,
			)
		}
	}
}

type ForArrIntBool struct {
	values []int
	key    int
	result bool
}

var lstArrIntBool = []ForArrIntBool{
	{[]int{3, 4, 5, 543, 53, 56, 9}, 32, false},
	{[]int{3, 4, 5, 543, 53, 56, 9}, 4, true},
}

func TestInArrayInt(t *testing.T) {
	for _, v := range lstArrIntBool {
		resp := InArrayInt(v.key, v.values)
		if resp != v.result {
			t.Error(
				"From", v.key, v.values,
				"\nexpected", v.result,
				"got", resp,
			)
		}
	}
}

type ForConvertDate struct {
	value  string
	result string
}

var lstForConvetDate = []ForConvertDate{
	{"6/2/2017", "2017-06-02"},
	{"6/23/2017", "2017-06-23"},
	{"11/12/2017", "2017-11-12"},
}

func TestConverData(t *testing.T) {

	for _, v := range lstForConvetDate {
		resp := ConverData(v.value)
		if resp != v.result {
			t.Error(
				"From", v.value,
				"expected", v.result,
				"got", resp,
			)
		}
	}
}
