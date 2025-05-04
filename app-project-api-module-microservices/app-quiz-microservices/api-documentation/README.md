Language API Documentation

end point app-microservice-language/
only post request accepted
Authorization: none
input:
{
"database_type": "sqlite",
"language_name": "English",
"iso_code_2": "en",
"iso_code_3": "eng",
"slug": "english"
}

success json output:
{
"message": "Successfully 'English' language created.",
"status": "success",
"data": {
"database_type": "sqlite",
"language_name": "English",
"iso_code_2": "en",
"iso_code_3": "eng",
"slug": "english"
}
}

error json output:
{
"message": "Only POST method is allowed",
"status": "error",
"data": null
}

error json output:
{
"message": "'English' language name already exists.",
"status": "error",
"data": {
"database_type": "sqlite",
"language_name": "English",
"iso_code_2": "en",
"iso_code_3": "eng",
"slug": "english"
}
}

- write api documentation html css like swagger.io
- section anchors herald menu style not like, please update it, and give space under herald menu
- add goto top button
- don't break code consistency
- provide full code