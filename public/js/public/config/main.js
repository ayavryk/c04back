var appConfig = {
            "head":"Demo", // то что отображается в title
            "host":"http://c04.new",
            "server" : "http://c04.new/admin04/main", 
            "auth" : {
                "logon": "http://c04.new/auth",
                "logout": "http://c04.new/auth/logout",
            },
            "configPath": "public/config/{controller}/{method}.json",
            "editorPath": "public/tiny/index.html",           
            "homePage": "table/text",
            "menu": [
                {
                    "head":"Основное"
                },
                {   "main": true, 
                    "title": "Авторы",
                    "route": "/table/author"
                },
                {   "main": true,
                    "title": "Цитаты",
                    "route": "/table/text"
                },    
                {   "main": true,
                    "title": "Теги",
                    "route": "/table/tags"
                },{
                    "head":"Административное"
                },    
                {
                    "title": "Пользователи",
                    "route": "/table/users"
                },    
                {
                    "title": "Служебные_тексты",
                    "route": "/table/service_texts"
                },{
                    "head":"Отладка"
                },                       
                {
                    "title": "ConfigBuilder",
                    "route": "/configBuilder"
                },   
                {
                    "title": "Components",
                    "route": "/story"
                }
            ]
}
