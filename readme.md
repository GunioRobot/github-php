What is this?
=============

This is a simple `.php` file to make a clone of a GitHub repo to a server and afterwards it is possible pull aswell.

Why?
----

Im sick and tired that my webhost company does not have git installed so I can make git commands, and update my things.

I had to remove all, copy all and then configure all again because I forgot the version i was using and what I had done since last time i updated the server.

How does it work
----------------

Inside the php file in the top there are some configurations and you should set them to your own needs and then browse to the file.

Here will be two options "clone" and "pull". 

The clone will copy the whole repo to your server and then make a little .txt file containing the latest commit id.

The pull option will check against the .txt file made in "clone" and then find newer commits. All newer commits will then be copied to youre server one at the time (add, modified & removed).

Both options will echo out a log of what it has done for you.

TODO's
------

* Make it work on private repo's
* Make some kind of security ?
* Code cleanup!
* Make that damn flush thing work ....
* MORE DAMN TESTING

more?

TCarlsen