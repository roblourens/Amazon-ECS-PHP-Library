<?php

include '../../settings.php';

try
{
    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'US');

    // First of all you have to set an another ResponseGroup. If not the request would not be successful
    // Possible Responsegroups: BrowseNodeInfo,MostGifted,NewReleases,MostWishedFor,TopSellers
    $amazonEcs->responseGroup('BrowseNodeInfo');

    // Then browse a node like this:  nodeId (See: http://docs.amazonwebservices.com/AWSECommerceService/2010-09-01/DG/index.html?BrowseNodeIDs.html)
    // For example: 542064 on German Amazon is: Software
    $response = $amazonEcs->browseNodeLookup(1000);
    print_r($response);

    // The response contains now some information about this Node and its children, ancestors etc.
    // So we picked out one noteId of the childelements: 408306 -> Programmierung (Programming).
    // Now we want to browse this node
    $response = $amazonEcs->responseGroup('TopSellers')->browseNodeLookup(1);
    print_r($response);

    // Picking out one childNodeId again
    // 466484 -> Programmiersprachen (Programming languages)
    $response = $amazonEcs->browseNodeLookup(466484);
    //var_dump($response);

    // I think its enough now.. the basics should be clear. You can browse deeper and deeper this way.
    // Now we want to display the TopSellers in this node.
    // So we have to change the responseGroup.
    $response = $amazonEcs->responseGroup('BrowseNodeInfo,TopSellers')->browseNodeLookup(466484);
    //var_dump($response);

    // This is compatible with the associateTag feature. Feel free to use it here.
    $response = $amazonEcs->associateTag(AWS_ASSOCIATE_TAG)->browseNodeLookup(466484);
    //var_dump($response);

    // At this moment when i'm writing this this ASIN: 383621640X is the TopSeller in this Node
    // So i want to fetch all Infos about it.
    // I have to set back my responseGroup to e.g. Large then starting the lookup request:
    $response = $amazonEcs->responseGroup('Large')->lookup('383621640X');
    //var_dump($response);

    // If you want to use a nodeId combined with your search operation you can add the nodeId as parameter:
    // It is necessary to provide a category first.
    $response = $amazonEcs->category('Software')->responseGroup('Large')->search('PHP 5', 466484);
    //var_dump($response);

    // Searching something and returning some nodes found for this keyword.
    $response = $amazonEcs->responseGroup('BrowseNodes')->search('Visual Studio');
    //var_dump($response);
}
catch(Exception $e)
{
  echo $e->getMessage();
}

if ("cli" !== PHP_SAPI)
{
    echo "</pre>";
}
