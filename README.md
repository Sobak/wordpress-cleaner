WordPress Cleaner
=================

Removes most common junk from your WordPress database. Can significantly decrease
size of your tables. Currently script recognizes following types of unneeded data:

* post revisions (disabled by default)
* auto drafts
* spam comments
* unapproved comments
* orphan commentmeta
* orphan postmeta
* transient options
* akismet commentmeta

Format of tasks
---------------

It is very easy to extend script functionallity. Look at the format of `WordpressCleaner::$tasks`
entry:

```php
'spam_comments' => [
    'name' => 'Spam comments',
    'desc' => 'Removes comments marked as spam',
    'query' => "DELETE FROM {prefix}comments WHERE comment_approved = 'spam'",
    'default' => true
]
```

Element's key is used as a internal task identifier. Keys in associative array are rather
self descriptive.

### More advanced tasks
You might need more complex logic than just execute query. If so, pass `false` as `query`
value. Script will then look for `WordpressCleaner::taskYourTaskId()` method - it can't
take any arguments and must return number of deleted items. Look at the example.

```php
// Entry in WordpressCleaner::$task
'advanced_task' => [
    'name' => 'Advanced query',
    'desc' => 'More than just a query!',
    'query' => false,
    'default' => false
]

// Custom method in WordpressCleaner.php
private taskAdvancedTask() {
    // Your awesome complex logic

    return 666; // Return number of removed rows/items
}
```

Contributions and/or feedback are most certainly welcome!
