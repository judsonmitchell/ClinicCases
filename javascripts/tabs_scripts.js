function changeTab(chosen)
{
var singleTab;

var navTabs = new Array()
navTabs[0] = "tab1";
navTabs[1] = "tab2";
navTabs[2] = "tab3";
navTabs[3] = "tab4";
navTabs[5] = "tab5";

for (singleTab in navTabs)
{
tabTarget = document.getElementById(navTabs[singleTab]);
refId = 'a' + navTabs[singleTab];
tabHref = document.getElementById(refId);
  if (navTabs[singleTab] !== chosen)
  {
      tabTarget.style.backgroundColor = '#E7F1F8';
      refId = 'a' + navTabs[singleTab];
      tabHref = document.getElementById(refId);
      tabHref.style.backgroundColor = '#E7F1F8';
      
      
  }
  else if (navTabs[singleTab] == chosen)
    {
    tabTarget.style.backgroundColor = 'white';
    refId = 'a' + navTabs[singleTab];
      tabHref = document.getElementById(refId);
      tabHref.style.backgroundColor = 'white';
    
     
  
    }
}
}































































































































































