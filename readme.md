What is this?
=============
This is a simple php file to make a clone of a GitHub repo to a server, and later make pulls from it.

Why?
====
Im sick and tied that my webhost company dont have git installed so I can make pull commands, and update my things.

I had to remove all, copy all and then config all agian becouse I forget what version it is and what I have done since last time i updated the server.

How does it work
================
Inside the php file in the top there are some configurations and you should set them to your own needs and then browse to the file.

Here will be two options "clone" and "pull". 

The clone will copy the whole repo to your server and then make a little .txt file containing the latest commit id.

The pull option will check against the .txt file made in "clone" and then find newer commits. All newer commits will then be copied to youre server one at the time (add, modified & removed).

Both options will echo out a log of what it has done for you.

TODO's
======
* make it work on private repo's
* make some kind of secrurity ?
* code cleanup!
* make that daam flush thig work ....
* MORE DAAM TESTING

more?

TCarlsen