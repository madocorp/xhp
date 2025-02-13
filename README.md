# X11 for PHP

This code implements X11 protocol in PHP language. It's not a high level
library, it's just a tool, that makes easier to communicate with the X server.
You have to know what to say, and how to interpret the answers. The request
functions are very similar to the byte order on the wire, so the X11
documentation may help. See:

https://x.org/releases/X11R7.7/doc/xproto/x11protocol.html

There is a simple "Hello World!" application under the Test directory, that
makes a siple window with title, and exits at pressing ESC key or window close
event. The test.php next to it calls all requests at least once and makes big
mess on your screen.