Opauth-TencentQQ
=============
Opauth strategy for Sina Weibo authentication.

Based on Opauth's Facebook Oauth2 Strategy

Getting started
----------------
0. Make sure your cake installation supports UTF8

1. Install Opauth-Sina:
   ```bash
   cd path_to_opauth/Strategy
   git clone git://github.com/fsgmhoward/opauth-tencentqq.git TencentQQ
   ```
2. Create Tencent QQ Application at http://open.qq.com/ or http://connect.qq.com/  <*P.S.: The website data on these two sites are SEPERATED*>
   - It is a web application
   - Callback: http://path_to_opauth/qq_callback

3. Configure Opauth-Sina Weibo strategy with `key` and `secret`.

4. Direct user to `http://path_to_opauth/tencentqq` to authenticate

Strategy configuration
----------------------

Required parameters:

```php
<?php
'TencentQQ' => array(
	'key' => 'YOUR APP KEY',
	'secret' => 'YOUR APP SECRET'
)
```

License
---------
Opauth-TencentQQ is MIT Licensed  

The MIT License (MIT)

Copyright (c) 2015 fsgmhoward

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.