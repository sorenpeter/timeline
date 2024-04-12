# timeline - create you own social website

(Created by sørenpeter / www.darch.dk)

So are you also tired and depress by having to use big tech's social medias to stay connected with friends and interesting people?
And are you a creative human, who want back control over you art, music, words, code, instead of having to shuehorn your soul into the grid of blackbox algoritmes?
Then it is you, who I've made timeline for.

## 🧶 What is timeline and twtxt/yarn?

timeline is a handfull of small files that you upload to a server and access it via a browser.
It then gives you an interface for posting words, links (and images too, soon), as well as following other peoples feeds and enganging in conversations by posting replies.

For the social features timeline are using the [twtxt](https://twtxt.readthedocs.io) format and most of the [yarn.social extentions](https://dev.twtxt.net).
timeline also support its own flavour of webmentions, so it is posible to be notified about `@mentions` from feeds you are not currenly/yet following.
You can also search for others feeds using webfinger, if they got that set up, like it's the case for most yarn.social pods.

My visions for timeline is to bring back the fun and quirckness of Geocities (1990s) Myspace (2000s) of creating a personlised place for you to express yourself online and also being able to follow others who inspire(s?) you. At the same time provide a good looking basic design with the help of [simple.css](TODO), which allows you to customise the look and feel. Even to the level where timeline align with the design of excsing webpage, like I did on: [darch.dk/timeline](https://darch.dk/timeline).


## 🪐 Features

// Screenshot and captions

- **Social reader, Profile and Gallery view** (TODO: Screenshots)

	- **Basic timeline** A timeline view similar to how your twitter or facebook feed would look like with text, hash-tags and images.

	- **Profile**

	- **Gallery** And a gallery view similar to instagram, where alle your posted images are presented in a grid design.


- **twtxt/yarn client and server**
	- Same post on twtxt.net, darch.dk/timeline, raw and jenny or tt

- **Cusosmines the look and feel.**

- **Webmentions**

- **Webfinder**


## 🚨 DISCLAIMER / WARNING

I'm not a professional programmer, so this software might lead to data lose or making your website unsecure in unforeseen ways.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## 🛠 Installation and setup

0. You need to have a webhosting with **PHP 8** and perferable running Apache or similar for timeline to work.
	
	> There are free options, but I would suggest that you pay for your hosting and also get a nice domain, so you have more ownership over your data and online idetenty.

1. Download the code from https://github.com/sorenpeter/timeline as a zip

2. Upload the content of the zip to you webhosting using a FTP client
	
	- The default would be to put eveything from within the timeline-main folder in the root so you will have:

	```
	www.example.net/timeline/            (go here to see your timeline)
	www.example.net/timeline/gallery/    (go here to see your gallery)
	www.example.net/timeline/post/       (go here to post to your feed)
	www.example.net/twtxt.txt         (where you feed lives and other can follow you)
	www.example.net/avatar.png        (your pretty picture)
	www.example.net/README.md         (can be deleted)
	```

	- or you can rename the folder `timeline` to something else

3. Go to the `private` folder and make a copy of `config_template.ini` and save it as `config.ini`

4. Open `config.ini` and edit the setting to you liking and setup

5. Open up `www.example.net/timeline/` in your browser and check for any errors


## 🎨 Customization

* Upload your own `avatar.png` (can also be a .jpg or .gif)
	- Edit your `twtxt.txt` and `config.ini` with the correct path

* Open up `custom.css` and try out the provided themes by uncommenting the code

* Change the colors and other elements in `custom.css` to you liking


# TODO

## Bugs

- Fix issues with parsing markdown vs. twtxt syntax

## Features

- UI for uploading images
- UI buttoms for markdown when making post

- Backend: Thumbnail cache support, to avoid loading all images in full resolution when viewing a gallery

# 🙏 Credits / shoutouts

* [twtxt](https://twtxt.readthedocs.io) - the original decentralised, minimalist microblogging service for hackers

* [yarn.social](https://yarn.social) - the multi-user pods allowed everyone to use twtxt as a social media without selfhosting

- List of other twtxt tools

## Code and more by others

* twtxt-php

* Slimdown // or make our own basic parser: Lists, block quotes, code/blocks, links, images

*

/*
	* [picoblog](https://0xff.nu/picoblog) - the PHP backend that pixelblog are using for rendering the timeline view
	* [Thumb](https://github.com/jamiebicknell/Thumb) - a simple thumbnail generation script written in PHP
	* [Tabler Icons](https://tabler-icons.io) - the icon set that is used for navigation item
	* [ekkoflok](https://ekkoflok.dk) - thanks for inspiration and help with PHP gallery functionality 
*/