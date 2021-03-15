# Tree-Approach  
  
This is an approach to model a tree, but ***it´s not really a binary tree***,   
it´s more like a nested list. However, works like a tree!  
  
The concept is, there are many nodes, each node can point to a parentNode,  
and the relation is saved into a parent_nodes table, i have used two tables,  
one table to store all nodes separatelly, and parent_nodes for represent a  
node pointing to another, like a child pointing to father.  

A node can be root node if is not pointing to any another node.  
  
But, nodes, parentNodes, how can use this structure?  
  
I created a controller called TreeController, to manage the structure.  
  
If we need to see children of a node, there is a method to get the childrens.  
  
If we need to create a node, there is a method to create and store a new node  
into DB, of course aditionally we need to store the relationship between  
 node and its parent (father), we can use setParent method (this method   
can avoid circular references before make the relation in the database)  

Finally, you can remove a node with some limitations, you cannot remove  
 a node if the node has childrens (this limitation is intented to be   
removed at the future versions)  
  
  
## Installation:

Some commands to run the container:  

+ docker-composer up -d
+ Don´t forget: Run composer install:
    + composer install

## Usage:  

##### After that, you can see the database with Adminer at:  

localhost:9000  

+ Credentials to Adminer::  
    + Host: treehost  
    + User: tree_user  
    + Password: tree_password  
    + Database: tree_db  
 
  
You will see the database with no tables inside.  

To get the table structure, run index.php:  
  
http://localhost:85  
  
After that, the tables will be loaded (only first time, or if the tables does  
not exists).  
  
To save a new node, perform a POST call to localhost:85 (with POSTMAN or INSOMNIA):    

+ URL: http://localhost:85?uc=createnode
+ BODY: { "name": "My new awesome category"}

To set parent node, perform a POST call to localhost:85:

+ URL: http://localhost:85?uc=setparentnode
+ BODY: { "node_id": 2, "parent_node_id": 1 }

To fetch entire tree, perform a GET call (with empty json body):

+ URL: http://localhost:85?uc=fetchjsontree

To update a node (change its name):

+ URL: http://localhost:85?uc=updatenode
+ BODY: { "node_id": 1, "new_name": "My new category name" }


