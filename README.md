# imagex-test


Image X test task


API 
Patch /jsonapi/customer/customer/
GET : /jsonapi/customer/customer/e0bac356-8fa1-47d8-8f7d-7cb49ef15151
PATCH : /jsonapi/customer/customer/e0bac356-8fa1-47d8-8f7d-7cb49ef15151
DELETE : /jsonapi/customer/customer/e0bac356-8fa1-47d8-8f7d-7cb49ef15151

POST : /jsonapi/customer/customer/

EXAMPLE PATCH 

{
  "data": {
    "type": "customer--customer",
    "id": "920ac9d3-9d49-4424-88f0-9bdcd79d6253",
    "attributes": {
      "name": "Test"
    }
  }
}


Import CSV
go to /csv_import/form
upload file
click «Save configuration»

You can import customers from form directly.
Also this form stores patch to CSV file for cron.
