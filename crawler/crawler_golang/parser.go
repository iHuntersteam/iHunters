package main

import (
	// "fmt"
	// "golang.org/x/net/html"
	// "io/ioutil"
	// "log"
	// "net/http"
	"regexp"
)


type PatternStrings struct {
	person_id int
	search_patterns []*regexp.Regexp
}


func regexpWord(persons map[int][]string) []PatternStrings {
	// return regexp for words in person
	patternStrArray := []PatternStrings{}
	for id, words := range persons {
		patterns := []*regexp.Regexp{}
		for _, word := range words {
			pat := regexp.MustCompile(`(?im)(?:\A|\z|\s|[[:graph:]])` + word + `(?:[[:graph:]]|\A|\z|\s)`) 
			patterns = append(patterns, pat)
		}
		patternStrArray = append(patternStrArray, PatternStrings{id, patterns})
	}
	return patternStrArray
}

func Parse(html_page []byte, patternStrArray []PatternStrings) map[int]int {
	// get count of words by html page
	counter := make(map[int]int)
	for _, patterns := range patternStrArray {
		for _, patt := range patterns.search_patterns {
			m := patt.FindAll(html_page, -1)
			counter[patterns.person_id] += len(m)
		}
	}
	return counter
}

