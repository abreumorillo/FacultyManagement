# ISTE-330/722 Database Connectivity and Access
## Faculty Research Database
### Team members: Yumi Kim, Jairo Veloz
### Documentation...
For autoloading and namespacing composer will be used. The main namespace for this application if **FRD**  (Faculty Research Database). The FRD namespace is basically mapped to server/src folder, so that means that everything under this folder structure will be automacally autoload by the composer autoload.

So far, this is the folder structure.
```
├── assets
│   ├── css
│   │   ├── angular-toastr.min.css
│   │   ├── animate.css
│   │   ├── bootstrap-theme.min.css
│   │   ├── bootstrap.min.css
│   │   ├── font-awesome.css
│   │   ├── font-awesome.min.css
│   │   └── style.css
│   ├── fonts
│   │   ├── FontAwesome.otf
│   │   ├── fontawesome-webfont.eot
│   │   ├── fontawesome-webfont.svg
│   │   ├── fontawesome-webfont.ttf
│   │   ├── fontawesome-webfont.woff
│   │   └── fontawesome-webfont.woff2
│   ├── images
│   └── js
│       ├── angular-animate.min.js
│       ├── angular-aria.min.js
│       ├── angular-cookies.min.js
│       ├── angular-messages.min.js
│       ├── angular-sanitize.min.js
│       ├── angular-toastr.tpls.min.js
│       ├── angular-touch.min.js
│       ├── angular-ui-router.min.js
│       ├── angular.min.js
│       ├── bootstrap.min.js
│       └── jquery-1.11.3.min.js
├── client
│   ├── app.js
│   ├── controllers
│   └── services
├── composer.json
├── composer.lock
├── documentation.md
├── index.php
├── readme.md
├── server
│   └── src
│       ├── Common
│       │   ├── CommonFunction.php
│       │   └── Exceptions
│       │       └── NotArrayException.php
│       ├── Controllers
│       ├── DAL
│       │   ├── Database.php
│       │   └── Repositories
│       │       ├── TestRepository.php
│       │       └── base
│       │           └── BaseRepository.php
│       ├── Interfaces
│       │   └── DbModelInterface.php
│       └── Model
│           ├── Paper.php
│           └── base
│               └── DbModel.php
├── template
└── vendor
    ├── autoload.php
    ├── composer
    │   ├── ClassLoader.php
    │   ├── LICENSE
    │   ├── autoload_classmap.php
    │   ├── autoload_namespaces.php
    │   ├── autoload_psr4.php
    │   ├── autoload_real.php
    │   └── installed.json
    └── vlucas
        └── phpdotenv
            ├── LICENSE.txt
            ├── README.md
            ├── composer.json
            └── src
                ├── Dotenv.php
                ├── Loader.php
                └── Validator.php

```
## Appication description

The purpose of this application is handle the publication of research papers by faculties and students.


## Language for each application layer

### Presentation Layer

The front-end was developed using the common web tecnologies: **HTML, CSS and JavaScript**.

Language: **JavaScript**

### Bussiness Layer
Language: **PHP**

### Data Layer
Language: **PHP**

## Use case diagram
//TODO: Create use cases


## Entity-relationship model
//TODO: design the diagrams

## User interface
In order to provide a sketch of the user interface we can use this resource https://balsamiq.com/?gclid=Cj0KEQjw75yxBRD78uqEnuG-5vcBEiQAQbaxSMbgu05vMG4lN3rF5sFhu0XGsYIgjgbSQReKBbrYEYYaAk1_8P8HAQ

## Key functionality
1. Search for papers
  * View the details of a selected paper.
2. Login functionality for faculty
3. Allow faculty to add new research paper
4. Allow faculty to browse previously published papers.
5. Allow faculty to view details of published papers
6. Login functionality for student
7. Aministration area for creating Faculty | students | keywords (TBD)

## Special Status Code
1. 422 for  validation error