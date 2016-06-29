package main

import (
	// "fmt"
	// "golang.org/x/net/html"
	// "io/ioutil"
	// "log"
	// "net/http"
	"regexp"
)



func regexpWord(word string) *regexp.Regexp {
	// return regexp for words in person
	return regexp.MustCompile(`(?im)(?:\A|\z|\s|[[:graph:]])` + word + `(?:[[:graph:]]|\A|\z|\s)`)
}

func Parse(html_page []byte, persons map[int][]string) map[int]int {
	// get count of words by html page
	counter := make(map[int]int)
	for id, words := range persons {
		for _, word := range words {
			m := regexpWord(word).FindAll(html_page, -1)
			counter[id] += len(m)
		}
	}
	return counter
}

