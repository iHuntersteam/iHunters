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
	person_id       int
	search_patterns []*regexp.Regexp
}

type Counter struct {
	person_id int
	count     int
}

func regexpWord(persons map[int][]string) (patternStrArray []PatternStrings) {
	// return regexp for words in person
	for id, words := range persons {
		patterns := []*regexp.Regexp{}
		for _, word := range words {
			pat := regexp.MustCompile(`(?im)(?:\A|\z|\s|[[:graph:]])` + word + `(?:[[:graph:]]|\A|\z|\s)`)
			patterns = append(patterns, pat)
		}
		patternStrArray = append(patternStrArray, PatternStrings{id, patterns})
	}
	return
}

func Parse(html_page []byte, patternStrArray []PatternStrings) (counter []Counter) {
	// get count of words by html page
	for _, patterns := range patternStrArray {
		c := 0
		for _, patt := range patterns.search_patterns {
			m := patt.FindAll(html_page, -1)
			c += len(m)
		}
		counter = append(counter, Counter{patterns.person_id, c})
	}
	return
}
