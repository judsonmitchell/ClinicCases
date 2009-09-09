
function spread(target,alt1,alt2)
{
Effect.Shrink(alt1, {direction: 'center'});
Effect.Shrink(alt2, {direction: 'center'});
var targ = document.getElementById(target);
targ.style.height = '88%';

}

function unspread(target,alt1,alt2)
{
Effect.Grow(alt1, {direction: 'center'});
Effect.Grow(alt2, {direction: 'center'});
var targ = document.getElementById(target);
targ.style.height = '28%';

}

function togg(on,off)
{
ontarget = document.getElementById(on);
offtarget = document.getElementById(off);
ontarget.style.display = 'block';
offtarget.style.display = 'none';


}
function spread2(target,alt1)
{
Effect.Shrink(alt1, {direction: 'center'});
var targ = document.getElementById(target);
targ.style.height = '88%';

}

function unspread2(target,alt1)
{
Effect.Grow(alt1, {direction: 'center'});
var targ = document.getElementById(target);
targ.style.height = '45%';

}
