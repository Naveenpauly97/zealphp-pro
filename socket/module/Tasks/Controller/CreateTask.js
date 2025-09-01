import WebSocketAPI from "../../exports";
import create from "../Api/create";
import TaskService from "../Service/TaskService";
import { socketConnection } from "/wsscript/connection/socket";


const CreateTask = async (event) => {
    const formdata = new FormData(event.currentTarget);
    const newTask = Object.fromEntries(formdata.entries());
    if (socketConnection.isConnected) {
        event.preventDefault();
        console.log(newTask);
        await create(newTask);
        const popup = document.getElementById('create-wrapper');
        popup.classList.remove('show');
        const activeId = document.querySelector('.filters a.btn-primary')?.id || null;
        console.log('Active anchor id:', activeId);
        WebSocketAPI.Task.getByStatus(activeId);

        // This Service call showing error need to fix this and make call by service in contorller
        // TaskService.createTask(newTask)
    }
    else {
        event.preventDefault();
        fetch('/tasks/create/1', {
            method: 'POST',
            body: formdata
        }).then((data) => {
            if (data.status === 200) {
                window.location.href = `/tasks`
            }
        })
    }
}

export default CreateTask;