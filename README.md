Task Manager CLI

Task Manager CLI is a command-line tool to manage tasks. It allows you to add, update, delete, and list tasks, as well as mark them as in-progress or done.
Installation

Clone the repository:
git clone https://github.com/nawrotech/task-manager-cli.git
cd task-manager-cli

Make sure the dependencies are installed via Composer:

composer install

Make the CLI executable by creating a symbolic link:

    chmod +x bin/task-cli
    sudo ln -s $(pwd)/bin/task-cli /usr/local/bin/task-cli

    Now, you can use the task-cli command directly from any location in your terminal.

    or

    You can run it with bin/task-cli <command>

Usage
Adding a New Task

To add a new task:

task-cli add "Buy groceries"

Output:

Task added successfully (ID: 1)

Updating and Deleting Tasks

To update an existing task:

task-cli update 1 "Buy groceries and cook dinner"

To delete a task:

task-cli delete 1

Marking a Task as In Progress or Done

To mark a task as in progress:

task-cli mark-in-progress 1

To mark a task as done:

task-cli mark-done 1

Listing All Tasks

To list all tasks:

task-cli list

Listing Tasks by Status

To list tasks by status:

    Done tasks:

task-cli list done

Tasks to do:

task-cli list todo

In-progress tasks:

task-cli list in-progress

Error Handling

The CLI application includes error handling for common issues, such as passing incorrect arguments or missing required inputs. If an error occurs, a friendly message will be displayed to the console.
Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your improvements.
License

This project is licensed under the MIT License.
