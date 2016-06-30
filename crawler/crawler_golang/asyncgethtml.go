package main

import (
	"fmt"
	"time"
)

type Rank struct {
	//structur for rank
	url int
	count map[int]int
}

func asyncHttpGets(urls map[int]string, persons map[int][]string) []*Rank {
	//asyncio get html pages and then get Rank of them
	patternStrArray := regexpWord(persons)
	ch := make(chan *Rank, len(urls)) // buffered
	responses := []*Rank{}
	for id, url := range urls {
		go func(url string, id int) {
			fmt.Printf("Fetching %s \n", url)
			html_page := Crawl(url)
			count := Parse(html_page, patternStrArray)
			ch <- &Rank{id, count}
		}(url, id)
	}

	//work with received Rank from channel ch
	for {
		select {
		case r := <-ch:
			responses = append(responses, r)
			if len(responses) == len(urls) {
				return responses
			}
		case <-time.After(50 * time.Millisecond):
			// fmt.Printf(".")
		}
	}
	return responses
}