/*
TreeMenu3 -- treemenu structure, style and behavior -- version 1.1 -- 2005-09-03
Copyright (C) 2005 Anders Nawroth (http://www.anders.nawroth.com/)
Released under LGPL, see http://treemenu.nawroth.com/source/license
*/

addEvent(window,'load',makeTreeMenu);
addEvent(window,'unload',saveTreeMenu);

function makeTreeMenu()
{
	if (document.getElementById && document.createElement)
	{
		var menu = document.getElementById('menu');
		var listItems = menu.getElementsByTagName('li');
		var oldTree = readCookie('treemenu');
		for (var i = 0; i < listItems.length; i++)
		{
			var li = listItems[i];
			if (li.className.indexOf('folder') != -1)
			{
				var span = document.createElement('span');
				li.insertBefore(span,li.firstChild);
				addEvent(span,'click',toggleClick);
				if (oldTree.length > 0)
				{
					var stat = oldTree.charAt(0);
					oldTree = oldTree.substring(1);
					if (stat == '-')
					{
						li.className = makeOpen(li.className);
					}
				}
			}
		}
	}
	if (document.all) /* IE bugfix, forces IE to re-render the menu */
	{
		menu.style.display = 'none';
		menu.style.display = 'block';
	}
}

function toggleClick()
{
	if (this.parentNode.className.indexOf('open') == -1)
	{
		this.parentNode.className = makeOpen(this.parentNode.className);
	}
	else
	{
		this.parentNode.className = this.parentNode.className.replace('open','closed');
	}
	if (document.all) /* IE bugfix */
	{
		menu.style.display = 'none';
		menu.style.display = 'block';
	}
}

function toggleItem(item)
{
}

function makeOpen(cn)
{
	if (cn.indexOf('open') == -1)
	{
		if (cn.indexOf('closed') == -1)
		{
			cn += ' open';
		}
		else
		{
			cn = cn.replace('closed','open');
		}
	}
	return cn;
}

function saveTreeMenu()
{
	var s = '';
	var menu = document.getElementById('menu');
	var listItems = menu.getElementsByTagName('li');
	for (var i = 0; i < listItems.length; i++)
	{
		var li = listItems[i];
		if (li.className.indexOf('folder') != -1)
		{
			if (li.className.indexOf('open') != -1)
			{
				s += '-';
			}
			else
			{
				s += '+';
			}
		}
	}
	createCookie('treemenu',s);
}

