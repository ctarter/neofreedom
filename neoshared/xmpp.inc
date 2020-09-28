<?php
/**
* Pub/Sub Protocol...
*
* <iq type='get'
    from='kmccallum@talk.neoxenos.org/news'
    to='pubsub.talk.neoxenos.org'
    id='nodes1'>
  <query xmlns='http://jabber.org/protocol/disco#items'/>
</iq>

<iq type='get'
    from='kmccallum@talk.neoxenos.org/hq'
    to='pubsub.talk.neoxenos.org'
    id='feature1'>
  <query xmlns='http://jabber.org/protocol/disco#info'/>
</iq>

Example 10. Account owner queries server regarding protocol support

<iq from='kmccallum@talk.neoxenos.org/hq'
    to='kmccallum@talk.neoxenos.org'
    id='disco1'
    type='get'>
  <query xmlns='http://jabber.org/protocol/disco#info'/>
</iq>

 <iq from='kmccallum@talk.neoxenos.org/hq'
    to='talk.neoxenos.org'
    id='A0' type='get'>
   <query xmlns='jabber:iq:register'/>
 </iq>

If the account owner's server supports PEP and the account is provisioned for PEP, the server MUST return an identity of "pubsub/pep" on behalf of the account (as well as a list of the namespaces and other features it supports, including all supported XEP-0060 features):

Example 11. Server communicates protocol support

<iq from='juliet@capulet.lit'
    to='juliet@capulet.lit/balcony'
    id='disco1'
    type='result'>
  <query xmlns='http://jabber.org/protocol/disco#info'>
    <identity category='account' type='registered'/>
    <identity category='pubsub' type='pep'/>
    <feature var='http://jabber.org/protocol/pubsub#access-presence'/>
    <feature var='http://jabber.org/protocol/pubsub#auto-create'/>
    <feature var='http://jabber.org/protocol/pubsub#auto-subscribe'/>
    <feature var='http://jabber.org/protocol/pubsub#config-node'/>
    <feature var='http://jabber.org/protocol/pubsub#create-and-configure'/>
    <feature var='http://jabber.org/protocol/pubsub#create-nodes'/>
    <feature var='http://jabber.org/protocol/pubsub#filtered-notifications'/>
    <feature var='http://jabber.org/protocol/pubsub#persistent-items'/>
    <feature var='http://jabber.org/protocol/pubsub#publish'/>
    <feature var='http://jabber.org/protocol/pubsub#retrieve-items'/>
    <feature var='http://jabber.org/protocol/pubsub#subscribe'/>
    ...
  </query>
</iq>


<iq from='kmccallum@talk.neoxenos.org/hq' type='set' id='news'>
  <pubsub xmlns='http://jabber.org/protocol/pubsub'>
    <publish node='http://talk.neoxenos.org/tune'>
      <item>
        <tune xmlns='http://jabber.org/protocol/tune'>
          <artist>Gerald Finzi</artist>
          <length>255</length>
          <source>Music for "Love's Labors Lost" (Suite for small orchestra)</source>
          <title>Introduction (Allegro vigoroso)</title>
          <track>1</track>
        </tune>
      </item>
    </publish>
  </pubsub>
</iq>

*/
?>