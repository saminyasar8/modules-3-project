<?php

define("TASKS_FILE", "task.json");

// Function to save tasks to a JSON file
function saveTasks(array $tasks): void
{
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

function loadTasks()
{
    if (!file_exists(TASKS_FILE)) {
        return [];
    }
    $data = file_get_contents(TASKS_FILE);
    return $data ? json_decode($data, true) : [];

}
// Load current tasks from the file
$tasks = loadTasks();




// Ensure we only attempt to process the form if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'task' field is set and not empty
    if (isset($_POST['task']) && !empty(trim($_POST['task']))) {



        // Add new task to the tasks array
        $tasks[] = [
            "task" => htmlspecialchars(trim($_POST["task"])),
            "done" => false,
        ];

        // Save the updated tasks array to the file
        saveTasks($tasks);

        // Redirect to refresh the page and avoid form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif(isset($_POST['delete'])){
        
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks);
        saveTasks($tasks);
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;  
    }elseif(isset($_POST['toggle'])){
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        saveTasks($tasks);
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    }
}










?>
<!-- UI -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }

        .task-card {
            border: 1px solid #ececec;
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task {
            color: #888;
        }

        .task-done {
            text-decoration: line-through;
            color: #888;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        ul {
            padding-left: 20px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="task-card">
            <h1> 🗒️Task_list To-Do App</h1>

            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h2>Task List</h2>
            <ul style="list-style: none; padding: 0;">
                <!-- TODO: Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->
                <?php if (empty($tasks)): ?>


                    <li>No tasks yet. Add one above!</li>
                <?php else: ?>
                    <?php foreach ($tasks as $index => $task): ?>
                        <!-- if there are tasks, display each task with a toggle and delete option -->


                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10 px;">TASK DONE</button>

                                <button type="submit"
                                    style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="task <?= $task['done'] ? 'task-done' : '' ?>">
                                        <?= $task['task'] ?>
                                    </span>

                                    </span>
                                </button>
                            </form>

                            <form method="POST">
                                <input type="" name="delete" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>

        </div>
    </div>
</body>

</html>
