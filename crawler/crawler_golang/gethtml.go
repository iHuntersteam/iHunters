package main

import (
	"io/ioutil"
	"log"
	"net/http"
)

func Crawl(url string) (bytes []byte) {
	//get html page (bytes)
	resp, err := http.Get(url)
	if err != nil {
		log.Panic("ERROR:", err)
	}
	defer resp.Body.Close()
	bytes, _ = ioutil.ReadAll(resp.Body)
	return
}
