# X11 for PHP

This code implements X11 protocol in PHP language. It's not a high level
library. It's just a tool, that makes easier to communicate with the X server.
You have to know what to say, and how to interpret the answers. The request
functions are very similar to the byte order on the wire, so the X11
documentation may help. See:

https://x.org/releases/X11R7.7/doc/xproto/x11protocol.html

There is a "Hello World!" application in the Test directory, that creates a
simple window with title, and exits when ESC key is pressed or the window is
closed. The test.php calls all requests at least once, and makes big mess on
your screen.
