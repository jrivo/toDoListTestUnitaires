### Installation SF 5 avec docker
```
// Initialiser les images docker
docker-compose build --no-cache
// Lancer les containers docker
docker-compose up -d
```

### Command to run unit tests
```
sudo docker-compose exec php bin/phpunit
```
### Adding a todo item
This explains how to add a todo item using this endpoint: http://0.0.0.0:8082/api/add-todo-item
#### Keys that can be sent in the POST request to 
todolist_id: required  
item_name: required  
item_content: required  
creation_date: this one is not required but you still need it since you have to wait 30 mins each time you add a todo list. the date format is "dd/mm/yyyy hr:min:sec" 
Example: "18/07/2021 20:50:00"  
#### Use cases
These are the use cases and the expected outputs

##### todo list id empty
```
{"result":"todo list id not specified"}
```
##### todo list wrong id/ doesn't exist in the database

```
{"result":"todo list not found"}
```
##### when the user tries to add an item with a name that already exits
```
{"result":"item name already exists"}
```
##### when the description contains more than 1000 characters
```
{"result":"Max characters for the content is 1000"}
```
##### when the user tried to add a second less than 30 min
```
{"result":"You have to wait 30 minutes every time you create an item"}
```
you can change the value of the attribute "creation_date" in order to test diffrent values ù
date format example: "18/07/2021 20:50:00"

##### when the date format isn't valid
```
{"result":"Date format is not valid"}
```
##### when the user adds the 8th item
```
{"result":"Date format is not valid", email:"sent"}
```



