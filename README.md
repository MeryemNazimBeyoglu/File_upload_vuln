here we have a simple vulnerable page..

we have a file upload vuln at this page..

lets see it

![Screenshot 2024-07-17 055044](https://github.com/user-attachments/assets/7ae7088f-fed9-4b02-9ca5-fe1f9ec6bbe4)

As you see above, when we tried to upload an PHP file we got an error message
lets check the brupsuite now




<img width="921" alt="upload" src="https://github.com/user-attachments/assets/da5434fa-b278-4596-afc3-8f6e6ca2a7f9">

here we can see that even though the system is returning an error message we are geting 200 ok response and that show us that we can upload our file successfully..

