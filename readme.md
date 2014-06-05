# Laravel 101
This is a basic setup for getting [laravel](http://laravel.com/) up and running locally and on a server _(media temple)_.	
Laravel 2.0 requires php 5.4.* If you are using MAMP and get the following `mcrypt error`. Here is a way to fix this [via](http://laravel.io/forum/02-08-2014-difficulty-installing-laravel-getting-error-mcrypt-php-extension-required).	
In terminal export a new path for php. 	
`export MAMP_PHP=/Applications/MAMP/bin/php/php5.4.10/bin export PATH="$MAMP_PHP:$PATH"`

### You need Composer.
Open up Terminal and see if you have brew installed by typing brew 
Now type composer to see if you have composer installed 

### Install Composer
You can install [Composer](https://getcomposer.org/) locally or globally. I recommd globally, but this will require `sudo` access. 

```
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

now type `composer` and you will see output in terminal.
```
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
```

### MAMP Server
Now create a new Laravel project using composer. Move to a directory that MAMP is running in.		
```
cd ~/Desktop/
mkdir laravel
cd laravel
```

### Make a new app
This will take a few minutes the first time you run the command.
```
composer create-project laravel/laravel app
cd app
```

### Add Ways Generators
I love to make things faster so right off the bat you should install [Jeffery Ways Generators](https://github.com/JeffreyWay/Laravel-4-Generators)

You can watch a nice video on how this addon can be user [here](https://dl.dropboxusercontent.com/u/774859/Work/Laravel-4-Generators/Get-Started-With-Laravel-Custom-Generators.mp4).

If you `ls` all the files in `/app` you should get this.
```
CONTRIBUTING.md	
app			
artisan			
bootstrap		
composer.json		
composer.lock		
phpunit.xml		
public			
readme.md		
server.php		
vendor
```

To install addons open `app/composer.json`
You can open the whole directory in sublime text by typing. *requires [sublime commands](https://www.sublimetext.com/docs/2/osx_command_line.html) to be installed* `subl .`

on `line 6:` of `composer.json` you will see `"require"` add  `"way/generators": "dev-master"`

The final result should look like this.
```
"require": {
		"laravel/framework": "4.2.*",
	    "way/generators": "dev-master"
	},
```

Now update composer. 
`composer update --dev`

>  **Note**:
You may get this warning `Fatal error: Allowed memory… `You can  fix this in [MAMP Pro](http://www.mamp.info/en/mamp-pro/) by editing the `php.ini` 
File -> Edit Template -> php -> php 5.5.3 ini
line 233 change memory_limit to `memory_limit = -1`

### Service Provider
You need to add the service provider. Open up `app/config/app.php` on line 97. Add the line: `’Way\Generators\GeneratorsServiceProvider’`

Now in terminal type `php artisan` and you will see a bunch of helper commands to build your app.

### Ready to make you app
At this point you have a basic installation of laravel installed locally. We can test out the site by visiting the document root of MAMP. i.e: [localhost:8888](http://www.mamp.info/en/mamp-pro/) 

> **Note**: Laravel needs to point to the public directory of your /app do this by changing to document root in MAMP Pro to `app/public`

Now visit [http://localhost:8888/](http://localhost:8888/)

***		

# Now lets start a simple app that inserts data

### MAMP Database
We need a database. MAMP will hook us up with this, visit.
[http://localhost:8888/MAMP/?language=English](http://localhost:8888/MAMP/?language=English)

I like using [Sequel Pro](http://www.sequelpro.com/), login with your credentials MAMPs defaults are:
```
host: localhost
user: root
pass: root
```

It may ask you to connect via socket click **yes**
click the top left menu `dropdown -> add database` (*name it whatever you want, I did app*)

### database.php
open `app/config/database.php`
`line 29:` should say `mysql`, this is our default database type

add the credentials to `line 55:`
```
'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'app',
			'username'  => 'root',
			'password'  => 'root',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),
```

Now our database is connected to Laravel. Note that this is a local database only, we will later need to change this to our media temple database. 

### Building a resource
In terminal we can run a resource command from `Way Generators`  this will make everything we need to build a simple API for to add/delete/update data		
`php artisan generate:resource post --fields="title:string”`	
In this line we create a resource called `post` make sure to use the singular version of the resource Laravel will automatticly create the plural. We then add some fields. This can be a comma separated list of [data types](http://laravel.com/docs/schema#adding-columns).

In your `routes.php` you will see 
`Route::resource('posts', 'PostsController’);`

This is a [resource route](http://laravel.com/docs/controllers#resource-controllers) you have all the verbs for a basic rest API. 

In Terminal type `php artisan routes` and your will see all the possible routes to update/edit/view posts. 

### Controller 
Open `app/controllers/PostsController.php`

This is were all the functionality for a `Post` happens. lets output all the post when you hit posts index. i.e: [http://localhost:8888/posts](http://localhost:8888/posts) 

In the `index() { }` function add
```
return Post::all();
```
This will return a json list off all the `Posts`. Now open [http://laraveldemo:8888/posts](http://localhost:8888/posts)

In the `create() { }` we want to show a simple web form to add a post to the database. Add this to the `create() { }` function.
```
return View::make('posts.create');
```
> **Note**: The dot syntax is referring to the path `views/posts/create.blade.php` Laravel using blade templates read more about it [here](http://laravel.com/docs/templates).

open [http://laraveldemo:8888/posts/create](http://laraveldemo:8888/posts/create)

### Form
now lets build the form.

here is a very simple form using the blade syntax. The `{{ your code here }}` is shorthand for writing `<?php echo  your code here ?>`

```
{{ Form::open(array('route' => 'posts.store')) }}
Title: {{ Form::text('title'); }}
{{ Form::submit('Create Post'); }}
{{ Form::close() }}
```

We are using the route `posts.store` because we will be calling the `store() {  }` function in the `PostsController.php`

### Save the Post
In the `store()` function try this

```
return Input::all();
```
and add a post in the browser with the form we just made, you will see all the inputs. 
open [http://laraveldemo:8888/posts/create](http://laraveldemo:8888/posts/create)


Now we can simply just add the post. 
```
public function store() {
	$post = new Post();
	$post->title = Input::get('title', 'no title');
	$post->save();
	return Redirect::back();
}
```

### List out all the Posts
lets add a list of all the `Post` in the `create.blade.php` view
```
<ul>
	@foreach(Post::all() as $post)
		<li> {{$post}} </li>
	@endforeach
</ul>
```

### Delete a Post

In the `PostsController.php delete() { }` function add 

```
public function destroy($id) {
	$post = Post::find($id);
	$post->delete();
	return Redirect::back();
}
```

In the `create form` we can add a simple form to submit a delete request.
```
@foreach(Post::all() as $post)
	<li> 
		Title: {{ $post->title }} 
		{{ Form::open(array('url' => 'posts/'.$post->id, 'method'=>'delete')) }}
		{{ Form::submit('Delete'); }}
		{{ Form::close() }}
	</li> 
@endforeach
```
### Modifying the Resource
What if we want to add another field to our resource. Laravel makes this very easy with `migrations`. Lets add another field called `body` to the `Post` model.

In terminal we are going to use another Way Generator to create a migration. Type:		
`php artisan generate:migration add_body_to_posts_table --fields="body:text”`		
>	Note: The Way Generator `generate:migration` is smart enough to read the keyword `add` from `add_body_to_posts_table` so that in the migration file will add the field. If we typed `create` it would create the table etc.

Now migrate the database.		
`php artisan migrate`		

You can now add another field to your create form like: `Body: {{ Form::text(‘body’); }}`. In your `PostsController` you will need to add the body attribute like: `$post->body = Input::get('body', 'empty body');`

### Publish to the web - MediaTemple
This is how to publish your app to mediatemple. Most of the steps will translate to other services but this is a good start.

### Setup Github
create an empty repo on [github](https://github.com/)

Now in terminal.
```
cd into your app folder 
cd ~/Desktop/app
```
make sure you `.ignore` has `/vendor` we do not want to upload all of these files.

Now run.
```
git init
git add .
git remote add origin git@github.com:username/demoapp.git
git commit -m 'first push of larvel app'
git push -u origin master
```

### Make a subdomain
Login to your [mediatemple](http://mediatemple.net/) account and click on `domain -> add new domain/service` and create a [subdomain](http://kb.mediatemple.net/questions/1897/How+do+I+add+a+domain+or+subdomain+to+my+server%3F)

### Turn on ssh
https://kb.mediatemple.net/questions/16/Connecting+via+SSH+to+your+server#gs

In terminal type:
`ssh serveradmin%youdomain.com@youdomain.com`

enter your password set for ssh access 

You are now logged in to the media temple server via ssh. Move into the new subdomain. **Note:** this sometimes takes a few minutes to propagate on the GS server.
`cd domains/subdomain.mydomain.com/`

### Add SSH Key to MediaTemple Server
When logged in to you server via ssh make sure you have a `.shh` folder

Type:
`ls ~/.ssh`

If not make this folder
`mkdir ~/.ssh`

Create a ssh key on the MediaTemple server
`ssh-keygen -t rsa -f ~/.ssh/github`


Print out the ssh key and add it to [Github](https://github.com/settings/ssh).
`cat  ~/.ssh/github.pub`
 
Now upload the file via ssh		
`cat id_rsa.pub | ssh serveradmin%yourdomain.com@yourdomain.com 'cat - >> ~/.ssh/github`	

You may need to add the `IdentityFile /home/server_number/users/.home/.ssh/github` to your config file.

See if it has been added.
`cat  ~/.ssh/config`

If not add `IdentityFile /home/server_number/users/.home/.ssh/github` to the config file.

```
nano ~/.ssh/config
(paste) IdentityFile /home/site_number/users/.home/.ssh/github
(hit ctr+x) - (yes) 
```
>  **Note**: The site_number is your [cluster number](https://ac.mediatemple.net/services/guide/gs/cluster.mt?id=591648), you can find this number under Server Guide in account center.

### Git Clone
Now that we have added the ssh key to Github and to the MediaTemple we can clone our project. 

make sure your are in the `cd domains/subdomain.mydomain.com/` and clone your git repo.

We now have `demoapp` and `html`. The `html` folder is the document root of the site. Laravel user `app/public` as the document root. We can create a symbolic link to bring us to this directory.

First remove the html folder.
`rm -rf html/`

Now create a symlink.
`ln -s demoapp/public/ ./html`

Now if you visit [http://demoapp.yourdomain.com/](http://demoapp.yourdomain.com/) you should get an error but this is ok. We just need to run `composer update`.

###Need Composer - Media Temple
First we need to install composer on the MediaTemple Server. 
Type [via](https://forum.mediatemple.net/topic/6927-here-is-how-to-install-and-use-composer/):		
```
curl -s https://getcomposer.org/installer | php -d allow_url_fopen=1 -d suhosin.executor.include.whitelist=phar
```

### PHP 5.5.*
We need php 5.4 or greater for Larvel 4.2. First you need to add it to the sub domain that we create.  You can do this by going to Account Center - Domains -> (you main gs server). Now click on PHP Settings, find your subdomain and select (latest) in the drop down. More info [here](https://kb.mediatemple.net/questions/244/How+can+I+specify+the+PHP+version+on+the+Grid%3F#gs) 

### Add Alias to Bash Profile
Add a alias to our `~/.bash_profile`

`nano ~/.bash_profile`
(**Paste**) `alias composer="php -d memory_limit=512M -d allow_url_fopen=1 -d `suhosin.executor.include.whitelist=phar composer.phar

Also add alias for the latest version of php
**(paste)** `alias php="/usr/bin/php-latest”`

**Save** `(ctr+x)`
**Reload the profile** `source ~/.bash_profile`

Now we can run composer commands. Move to the Laravel app.
`cd demoapp`
`composer update`

After the update visit your site and see if we get `Hello World`

### Remote Database
When we visit [http://demoapp.yourdomain.com/posts ](http://demoapp.yourdomain.com/posts) you should get an error. We need to setup our config file to use a MediaTemple database.

In the Account Center click on Server Guide. Under Database find your external host, something like *external-db.servernumber.gridserver.com* 
You will also need your username and password, create one if you have not. 
> **Note:** The external host is ip-blocking you may need to add your ip so that we can login via Sequal Pro. Go to Database Global Settings and add your IP at the bottom of the page. 

Now in Sequal Pro login to your database and add a database like we did locally. 
You can create a new database or just use an existing one a set a prefix in your `config/database.php` file.

Update your `config/data.php` file (locally in sublime) It should now look something like this. Note the `prefix: => ‘lavel_’,`
```
'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'external-db.gridservernumber.gridserver.com',
			'database'  => 'gridservernumber_databasename’,
			'username'  => 'username',
			'password'  => 'password',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => 'larvel_’,
		),
```

Add your changes to git and push to Github.
```
git add .
git commit -m 'edit database to remote'
git push
```

In your ssh window pull the new changes. Make sure you are in the git tempo. `cd domains/yourdomain/demoapp/`

Now Pull
`git pull`

This is how to push your changes to the remote server. 

### Are we connecting 
You can test this locally because our app is connecting to the external link. 

Visit your local instance of the app. [http://localhost:8888/posts](http://localhost:8888/posts) 

### Error Reporting
If you are getting an error turn on the debug so we can get a better look at what is going on. In `config/app.php` line 16 set `'debug' => true,`

now refresh the page. The error should give you more info about what went wrong.

### Migrate the database - Media Temple
If your refresh the page your will see that the Posts table is missing. We need to migrate the database. In your ssh window run.
`php artisan migrate`

Now refresh the page. At this point you should have a local and remote version of the app working. The database is remote but you can push to it locally. 

### GitHub Hooks
You may find it a bit tedious to have to add/push/pull from local to remote. You can easily setup a web hook so that every time you push to your git repo your remote server will automatically pull. 

In you local version of the app, create this file `/app/public/github.php`

Add this line of code, make sure you have the small ``` this tells php that it is a command to execute. 
`<?php `git pull`;`

Now add these files like you did before and push to github.
```
git add .
git commit -m 'auto pull php file'
git push
```

Now on your github repo page you need to connect the hook. Click on settings, then left hand menu  Webhooks & Services. Click add webhook.

**Under payload URL**: `http://demoapp.youdomain.com/github.php`		
**Sercet**: Leave blank		
**Event**: Just the push event		

Test this by changing a file locally like in `app/routes.php` make it say	
```
Route::get('/', function() {
	return 'hello world - pulled from github webhook';
});
```

Just push the code locally to github and refresh your remote webpage. Everything should be connected. 


# More…
This is just a start but there is so much more you can do. Watch tutorials here:
https://laracasts.com/ 
http://scotch.io/tutorials/simple-laravel-crud-with-resource-controllers

Thanks,
Todd



