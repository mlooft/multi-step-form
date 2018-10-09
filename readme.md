# Multi Step Form 
<!-- ![Multi Step Form Backend](https://github.com/mlooft/multi-step-form/blob/master/screenshot-1.jpg)
![Multi Step Form Frontend](https://github.com/mlooft/multi-step-form/blob/master/screenshot-2.jpg) -->

## Description 
Multi Step Form has a drag & drop enabled form builder for quick and intuitive creation of nice-looking multi step forms. Forms can be embedded on any page or post with shortcodes.

## Features

### ANIMATED PROGRESS BAR 
Use our customizable & animated progress bar to guide your users through your forms. Change the colors to match your corporate identity (CI).

### FULLY RESPONSIVE 
Perfect for mobile access! The frontend of Multi Step Form is fully responsive. It can be used and submitted on all devices. We made sure that the form output can be optimally displayed on all screen resolutions.

### DRAG & DROP 
Creating forms is as easy as never before. Use drag & drop to place the fields in your form. Fields can be moved and rearranged at any time. The individual steps can also be moved so that the sequence can be reordered or expanded effortlessly.

### EASY BACKEND 
The backend's structure is simple. Even less technical users can quickly understand the plugin and start creating forms.


## Contributing
We would really appreciate pull requests for features or bugfixes.

### Using `docker-compose` (recommended)

Here's what you need:
* a running Docker engine
* `npm` and `bower`

To get started, clone this repository to your local drive and run the following commands:

```
cd multi-step-form
npm install
bower install

docker-compose build
npm run gulp
```

The last command compiles the required CSS and packs the JavaScript code. It will watch the source files for changes and recompiles them on a change. Stop it with Ctrl+C.

Now you can start the required docker containers and install WordPress for the first time with:
```
docker-compose up -d

# The next step installs WP. You just need to run this once.
docker-compose run --rm wp-cli install-wp
```

If you now open http://localhost/ you should see an installed WordPress instance. You can login with "wordpress" as username and password.

If you want to change JS or LESS Code, start `gulp` with:
```
npm run gulp
```
and let it run while you work.

To stop the server, just run:
```
docker-compose down
```

### Using own server

Here's what you need:

* a local WordPress instance (preferably with XDebug)
* `npm`, `gulp` and `bower`

To get started with developing, please clone this repository directly to your `wp-content/plugins` directory and do the following:

```
cd multi-step-form
npm install
bower install
gulp
```

`gulp` will run in the background, watch for changes in the source files and update the distribution accordingly.

## Links
* **[Beginners Tutorial](http://mondula.com/en/2017/01/06/multi-step-form-anleitung/ "Multi Step Form | Beginners Tutorial")**

* **[Live demo](http://demo.multi-step-form.mondula.com/ "Multi Step Form | Live Demo")**

* **[Product page](http://multi-step-form.mondula.com/ "Multi Step Form")**

* **[Wordpress Plugin Repository](https://wordpress.org/plugins/multi-step-form/)**
