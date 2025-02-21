{
	"authHost": "advice.ci",
	"authEndpoint": "/broadcasting/auth",
	"clients": [
		{
			"appId": "1ef10095b23e8264",
			"key": "91460b0ee61198af6a3e856beecee7e0"
		}
	],
	"database": "redis",
	"databaseConfig": {
		"redis": {},
		"sqlite": {
			"databasePath": "/database/laravel-echo-server.sqlite"
		}
	},
	"devMode": true,
	"host": null,
	"port": "y",
	"protocol": "http",
	"socketio": {},
	"secureOptions": 67108864,
	"sslCertPath": "",
	"sslKeyPath": "",
	"sslCertChainPath": "",
	"sslPassphrase": "",
	"subscribers": {
		"http": true,
		"redis": true
	},
	"apiOriginAllow": {
		"allowCors": true,
		"allowOrigin": "y",
		"allowMethods": "GET, POST",
		"allowHeaders": "Origin, Content-Type, X-Auth-Token, X-Requested-With, Accept, Authorization, X-CSRF-TOKEN, X-Socket-Id"
	}
}