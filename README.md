# Backend
## 安裝:
直接執行

    docker-compose up -d 
    
安裝vendor套件
    
        docker-compose exec php composer install
        
修改環境變數
複製 .env.example 到.env

        cp .env.example .env
        
## 測試:
  直接訪問 http://127.0.0.1:8080/test?game_id=1&issue=20190903003 

## 其他回答:

   請看 [解答](answer.md) 



