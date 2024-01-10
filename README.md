**Step 1**

>RUN **_make start_** command.

**Step 2**

Replace docker-db-container-id with the container ID (docker ps)

>RUN _docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' **docker-db-container-id**_

![Screenshot from 2024-01-10 12-06-14](https://github.com/evanega3/canoe/assets/968968/fde18c80-688e-4d18-baa1-54f14fc691e9)

>UPDATE variable DATABASE_URL in .env file with the container IP.

![image](https://github.com/evanega3/canoe/assets/968968/d5e5d25b-17cc-456e-b485-6bde49bf611d)

**Step 3**

>RUN **make ssh-be-root**

**Step 4**

>EXECUTE **composer install**

After is done, run the following command:

>EXECUTE **php bin/console make:migration** command

![image](https://github.com/evanega3/canoe/assets/968968/ece8816f-c4a3-4522-859f-fa010ddd21b3)

After running the above command your database should look like this:

![image](https://github.com/evanega3/canoe/assets/968968migrations/Canoe.postman_collection.json/a8bd1bd6-73d7-4246-81b0-344f9f44f0ee)

**Step 5**

Import the Postman collection

[migrations/Canoe.postman_collection.json](https://github.com/evanega3/canoe/blob/master/migrations/Canoe.postman_collection.json)https://github.com/evanega3/canoe/blob/master/migrations/Canoe.postman_collection.json

You should be seeing something like the following after import:

![image](https://github.com/evanega3/canoe/assets/968968/0d686128-ff7c-44ff-b9a3-4915b9aad6f8)

**Step 6**

Now you can test everything, you should start by creating a new company.
