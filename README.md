# Team League

「Team League」は、RocketLeagueにおけるチームの管理を行うことを目的としたサイト
チームメンバー募集、チーム検索、チームプロフィール、スクリム募集、リーグ戦などを検討中

[機能一覧はこちら](https://gist.github.com/ichimatsu0627/ddad11259743ca0ccf79877c3aab560c)

## Deploy

### Local Development

#### Tools

- composer
- docker
- docker-compose

#### commands
 
```
$ cd team-league
$ composer install
$ cd team_league
$ docker-compose up -d
$ sh migrate-local.sh
```

### production
 
#### Tools

- composer
- google cloud console

#### secret files

```
team-league ┬ gae         ─ secret.yaml
            ├ secret      ─ team-leagut.json
            └ team_league ┬ export-production.sh
                          └ migrate-production.sh
```

#### commands
 
```
$ cd team-league
$ composer install
$ sh migrate-production.sh 
$ gcloud app deploy
```
