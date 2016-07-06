package main

import (
	"fmt"
	// "github.com/ziutek/mymysql/mysql"
	// _ "github.com/ziutek/mymysql/native" // Native engine
	"database/sql"
	_ "github.com/ziutek/mymysql/godrv"
	// _ "github.com/ziutek/mymysql/thrsafe" // Thread safe engine
)

var db *sql.DB

func NewDB(dataSourceName string) (*sql.DB, error) {

	var err error
	db, err = sql.Open("mymysql", dataSourceName)
	if err != nil {
		return nil, err
	}

	if err = db.Ping(); err != nil {
		return nil, err
	}
	return db, nil
}

type Page struct {
	id  int
	url string
}

func RescanNeededPages() ([]*Page, error) {

	rows, err := db.Query(`SELECT 
		id, url 
		FROM pages 
		WHERE rescan_needed = 1`)

	if err != nil {
		return nil, err
	}

	defer rows.Close()

	pages := make([]*Page, 0)

	for rows.Next() {

		page := new(Page)

		if err = rows.Scan(&page.id, &page.url); err != nil {
			return nil, err
		}

		fmt.Println(page)
	}

	if err := rows.Err(); err != nil {
		return nil, err
	}

	return pages, nil
}
