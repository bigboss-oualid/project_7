/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

setTimeout(function(){
    let img = document.createElement('img');
    img.src = "../images/logo-ico.png";
    img.alt = "logo";
    img.title = "BileMo";
    img.width = "25";
    img.height = "40";
    let svgs = document.getElementsByTagName('svg');
    svgs[1].replaceWith(img);

},1500);
