#!/bin/sh

carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" design -s -p --dir ../database/json/
carpenter -vv -s teamleague -d "ichimatsu:password@tcp(127.0.0.1:12001)" export -r "^m_.*" -d ../database/csv/
