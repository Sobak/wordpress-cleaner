<?php
class WordpressCleaner {
    public static $tasks = [
        'revisions' => [
            'name' => 'Post revisions',
            'desc' => 'Deletes all post revisions (past versions of the post). <strong>Select only if you understand consequences</strong>.',
            'query' => "DELETE FROM {prefix}posts WHERE post_type = 'revision'",
            'default' => false
        ],
        'autodrafts' => [
            'name' => 'Auto drafts',
            'desc' => 'Removes automatically created drafts of the posts',
            'query' => "DELETE FROM {prefix}posts WHERE post_status = 'auto-draft'",
            'default' => true
        ],
        'spam_comments' => [
            'name' => 'Spam comments',
            'desc' => 'Removes comments marked as spam',
            'query' => "DELETE FROM {prefix}comments WHERE comment_approved = 'spam'",
            'default' => true
        ],
        'unapproved_comments' => [
            'name' => 'Unapproved comments',
            'desc' => 'Removes comments pending in moderation queue',
            'query' => "DELETE FROM {prefix}comments WHERE comment_approved = '0'",
            'default' => false
        ],
        'orphan_commentmeta' => [
            'name' => 'Orphan comment metadata',
            'desc' => 'Deletes metadata not assigned to any of comments',
            'query' => "DELETE FROM {prefix}commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM {prefix}comments)",
            'default' => true
        ],
        'orphan_postmeta' => [
            'name' => 'Orphan post metadata',
            'desc' => 'Deletes metadata not assigned to any of posts',
            'query' => "DELETE FROM {prefix}postmeta WHERE post_id NOT IN (SELECT ID FROM {prefix}posts)",
            'default' => true
        ],
        'transient_options' => [
            'name' => 'Transient options',
            'desc' => 'Transient options are cache entries used by WordPress',
            'query' => "DELETE FROM {prefix}options WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'",
            'default' => true
        ],
        'akismet_commentmeta' => [
            'name' => 'Akismet commentmeta',
            'desc' => 'Removes metadata created by Akismet plugin',
            'query' => "DELETE FROM {prefix}commentmeta WHERE meta_key LIKE '%akismet%'",
            'default' => true
        ]
    ];

    private $mysqli;
    private $tablePrefix;

    public function __construct($mysqli, $tablePrefix) {
        $this->mysqli = $mysqli;
        $this->tablePrefix = $tablePrefix;
    }

    public function run() {
        $tasks = $_POST['tasks'];

        if (!is_array($tasks) || empty($tasks)) {
            echo '<p class="warning">Error: You have to select at least one task.</p>';
            exit;
        }

        foreach (array_keys($tasks) as $taskKey) {
            $task = self::$tasks[$taskKey];

            if ($task['query']) {
                $this->mysqli->query(str_replace('{prefix}', $this->tablePrefix, $task['query']));
                self::$tasks[$taskKey]['result'] = $this->mysqli->affected_rows;
            } else {
                // This task uses callback
                $taskMethod = 'task' . $this->snakeToCamelCase($taskKey);
                self::$tasks[$taskKey]['result'] = $this->$taskMethod();
            }
        }
    }

    private function snakeToCamelCase($string) {
        // In fact it is PascalCase, but hush ;)
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}
