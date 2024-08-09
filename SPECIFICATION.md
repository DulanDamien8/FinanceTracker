# SPECIFICATION FOR A WEBSITE

## Overview

In this project milestone we implemented the dynamic page functionalities
with the use of PHP with CSS styling and HTML forms. Additionally we implemented
mock databases with CSV files, to provide Alpha version of the website.

Our project's main objective is to help individual persons to track 
their expenses, show them their current balance, as well as provide
an intuitive list of all their expenses as a scrollable history. Additionaly,
we allow the users to take full controll of their data by providing
pages for the edition, addition and deletion of the data, which includes 
the user's own account.

The target audience of our project is everyday idividual, who wants to
take controll of their finances and account balance. Additionaly, individuals
who want to have a quick and easily accessible personal finance tracker on 
the go wherever they have the internet connection.

## Team

Marek Kopania - mkopan@taltech.ee

    "about, login, register, myaccount" pages 
    with their styling using CSS,
    navbar html and css code,
    idea of usage FontAwesome icons and integration
    into the html code 

    home, login, register, myaccount pages 
    with php functionalities, as well as 
    management of csv files and overall
    structure of the project

    home, login, register, myaccount pages for the db
    connection, javascript for the home page and myaccount
    page, session management, cookie authorization


Mattias Nõmm - matnom@taltech.ee

    "addexpense, editexpense" pages including
    CSS styling and linking them to the navigation
    bar and elements of the GUI,
    proper commenting of the sections of code

    addexpense, editexpense, home pages php 
    functionalities and css styling of all forms
    for invalid and valid inputs

    editexpense, addexpense pages for the db connection,
    javascript for the myaccount page, session management,
    cookie authorization


Dulan Damien Candauda Arachchiege - ducand@taltech.ee

    "index (Home)" page with the styling of the entire
    page, creation of dummy data for the list of 
    expenses and proper styling of the list using CSS,
    proper commenting of the sections of code

    addexpense, editexpense, home pages php 
    functionalities and css styling of all forms
    for invalid and valid inputs

    editexpense, addexpense pages for the db connection,
    javascript for the myaccount page, session management,
    cookie authorization

## Goals, Objectives and Phases

### Objective

Our objective is to provide to our users a quick, intuitive and easy to use
web application. The UX is the main focus of this project. The UI is supposed to
be as clear and clean as possible. Additionally the UI should be easy to follow,
even for users who see the website for the first time.
In later stages we plan on implementing security features for the data 
stored in the databases, clear and informative notification system, as well
as accessibility features. Finally, the application should provide our users
with a pleasant software, with which they can track their finances on the go.

### Goals

* Intuitive and clear UI
* Seamless UX
* Quick server operatability
* Security of data
* Informative notification system
* Accessibility features

### Phases

1. Project milestone #1 (HTML/CSS)
2. Project milestone #2 (PHP)
3. Project milestone #3 (SQL/JS)

## Content Structure

### Site map

```text
Home
  +--Add Expense
  +--Edit Expense
  +--MyAccount
  +--Login
  |    +--Register
  +--About

```

### Content Types
```text
Home:
    ribbon
        image - profile avatar
        user profile info
        form for adding income
        buttons for adding income
          and a new expense
    expenses list
        button to download expenses as csv
        item of an expense
        "Edit" and "Delete" buttons
          for each expense item 

My Account:
    user personal information
    form for edition of user details
        name
        email
        password
        password confirmation
    buttons for enabling edition, 
      saving changes and deleting account 

Login:
    form for logging in
        email
        password
    button for login

Register:
    form for registering a new account
        name
        email
        password
        password confirmation
    button for registering

Add Expense:
    form for adding an expense
        name
        amount
        expense type (category)
        payment type
        date of expense
    button for adding the expense

Edit Expense:
    form for editing an expense
        name
        amount
        expense type (category)
        payment type
        date of expense
    button for saving the changes

About:
    general description of a
      website purpose and content
    button to "Get Started"
```

### Page Templates

Everything that can be seen on the website is a ready version of the webpage.
The pages are finished in terms of the content and visuals.
The Home page's expenses list is ordered by date from the newest to the oldest
once the DB connection and PHP server is set up.
The My Account page got the functionality of enabling the form fields and a
"Delete" button, after clicking the "Edit" button. This was done with JS.
Othere than that, we optimized the "Ribbon" CSS, so that when resizing the window
the ribbon hides itself and a button to expand the ribbon shows. This was done with
CSS and JQuery.

We implemented the full database connection and operability with session and cookies 
for authorization

## Design

When it comes to design, the entire website is finished.

## Functionality

The main way to navigate on our website is through the 
navigation bar located at the top of our webpages.
By clicking each item on the navigation bar you can
access different pages.

To login you need to navigate to the Login page and fill out
the required "email" and "password" fields. Then after clicking 
the login button, if the data is correct you will be logged in.
From the level of the Login page, to register, you need to click 
the "Sign Up" button, which will redirect you to the Register page. 
There you need to fill every field to successfully register a new 
account. After doing that, to register, you need to click the "Sign Up" button.

My Account page is dedicated to the edition of personal information 
of the account. There you can change the name, email and password of the 
account. To enable the edition of the details you need to click 
the "Edit" button. To save changes you need to click the "Save" button. 
To delete the account you need to click the "Delete" button. 
All fields and "Save" and "Delete" buttons will be enabled after 
clicking the "Edit" button. After that you can edit account info,
or delete your account. If the second is selected, then a popup will
show asking for confirmation. If confirmed the account with all data will be 
deleted and the user will be logged out destroying the session and cookies.

The Home page contains the list of expenses with a button to download
user's list of expenses and a ribbon with user information, a form to 
add income to the account and an "Add Income" button to confirm the 
addition of income to the account and a button "Add Expense". 
Clicking the button "Add Expense" redirects you to the page where you 
can add a new expense. 

All the fields of the form need to be filled to 
successfully create a new expense. Then, by clicking the "Add" button, 
you can add a new expense for the account.

Inside the list of expenses in the Home page, each item has 
two buttons "Edit" and "Delete". Clicking the "Delete" button will 
remove the expense from the list. Clicking the "Edit" button will 
redirect you to the Edit Expense page, where you can change the 
information of the selected expense. There, by clicking the "Save" 
button you can confirm the changes of the details.

About page is a page where the general information about the 
purpose and functionality of the webpage is displayed for the user. 
There is also a "Get Started" button, which redirects you to the Login 
page.

Previously, the website had only HTML and integrated PHP 
functionality, for server side processing. Now it has database connection
as well as the javascript with jquery functionalities. This allows the validation 
mechanisms for any input field that can be found on, the login/signup 
page, register page, my account page, add expense page and 
edit expense page. This is to make sure that the user enters valid data.
Additionally, all the data is validated and checked if it matches the security
criteria, whether it be the GET or POST methods. 

Moreover, with the use of PHP users are now able to add their expenses, 
and to download a csv file containing all the added expenses.

Another important functionality that has been added to the website 
is that the users are now able to register on the website with their 
credentials and then login to their account using valid credentials.
They are able to create personal shopping list, edit it, remove items from it
and add newly acquired income. The page calculates the balance of the users automatically.

Additionally upon successful addition, editing, login and registration 
the user will be redirected to the home page. Other redirections have also been
implemented upon success or failure to comply with the requirements.


## Browser Support

* Chrome/Chromium
* Firefox
* Edge
* mobile browsers (Chrome, Firefox)

## Hosting

ENOS.


