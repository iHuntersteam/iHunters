package main

import (
	"fmt"
	"testing"
)

var persons = map[int][]string {
		1: {"путин", "путину", "путиным", "путина"},
		2: {"Медведев", "Медведевым", "медведеву"},
	}

func TestParseHtml(t *testing.T) {
	// test for parse html page with persons
	// result: map[2:0 1:30] 2, 1 - person ids; 0, 30 - count words

	fmt.Println("TestParseHtml")
	
	html_page := Crawl("http://ria.ru/tourism/20160629/1454284149.html")

	result := Parse(html_page, persons)

	fmt.Println(result)

}

func TestAsyncoParseHtml(t *testing.T) {
	// test for asyncio parse html page with persons
	// result list of Rank's structure [html_page_id [person_id: count, ...]

	fmt.Println("TestAsyncoParseHtml")
	urls := map[int]string {
		1000: "http://ria.ru/tourism/20160629/1454403549.html",
		2000: "http://ria.ru/tourism/20160629/1454284149.html",
	}

	results := asyncHttpGets(urls, persons)
	for _, result := range results {
		fmt.Println(result.url, result.count)
	}
	
}
