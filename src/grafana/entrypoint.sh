#!/usr/bin/env sh

url="http://$GF_SECURITY_ADMIN_USER:$GF_SECURITY_ADMIN_PASSWORD@localhost:3000"

post() {
    curl -s -XPOST -d "$1" \
        -H 'Content-Type: application/json;charset=UTF-8' \
        "$url$2" 2> /dev/null
}

if [ ! -f "/var/lib/grafana/.init" ]; then
    exec /run.sh $@ &

    until curl -s "$url/api/datasources" 2> /dev/null; do
      echo "Waiting for the URL to be available: $url"
      sleep 1
    done

    curl -v -H 'Content-Type: application/json' -d @/etc/grafana/datasources/json.json "$url/api/datasources"
    curl -v -H 'Content-Type: application/json' -d @/etc/grafana/dashboards/example.json "$url/api/dashboards/db"

    touch "/var/lib/grafana/.init"
    
    kill $(pgrep grafana)
fi

exec /run.sh $@
