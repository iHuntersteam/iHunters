package main

import (
	// "crawler_golang/models"
	"fmt"
	"time"
)

type RankUrl struct {
	//structur for rank
	url   int
	count []Counter
}

func asyncHttpGets(urls map[int]string, persons map[int][]string) (responses []*RankUrl) {
	//asyncio get html pages and then get Rank of them
	patternStrArray := regexpWord(persons)
	ch := make(chan *RankUrl, len(urls)) // buffered
	for id, url := range urls {
		go func(url string, id int) {
			fmt.Printf("Fetching %s \n", url)
			html_page := Crawl(url)
			count := Parse(html_page, patternStrArray)
			ch <- &RankUrl{id, count}
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
	return
}
