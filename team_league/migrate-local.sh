#!/bin/sh

carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" design -s -p --dir ../database/backup/json/
carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" export -r "^m_.*" -d ../database/backup/csv/
carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" build --dir ../database/json/
carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" import --dir ../database/csv/

