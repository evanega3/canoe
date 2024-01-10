**Clarifications**

I did not use Fund Managers table because Symfony does this relations behind seens, in this case the relationship ManyToOne from Funds to Companies. Specified on fields **Funds.company** and **Companies.funds**. Also added 3 extra fields per table (created_at, updated_at and deleted_at for soft-deletes). I tried to make it as clean as I could, using DTO's for variables processing on end-point requests, also implemented UseCases for Created, Updated and Search.

**ER Diagram**

![image](https://github.com/evanega3/canoe/assets/968968/e4dd84fb-1354-44cd-825c-36c79efbdaa7)



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

![image](https://github.com/evanega3/canoe/assets/968968/fbfd7d25-898d-4377-b063-29514f4a0547)

**Step 5**

Import the Postman collection

[migrations/Canoe.postman_collection.json](https://github.com/evanega3/canoe/blob/master/migrations/Canoe.postman_collection.json)https://github.com/evanega3/canoe/blob/master/migrations/Canoe.postman_collection.json

You should be seeing something like the following after import:

![image](https://github.com/evanega3/canoe/assets/968968/4ae612e3-cc64-47bd-8a53-80c40935f0c5)

**Step 6**

Now you can test everything, you should start by creating a new company.
