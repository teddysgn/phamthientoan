<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Login</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   </head>
   <body class="bg-mainBlack text-white">
      <!-- component -->
      <div class="bg-sky-100 flex justify-center items-center h-screen">
         <!-- Left: Image -->
         <div class="w-1/2 h-screen hidden lg:block">
            <img src="https://img.freepik.com/fotos-premium/imagen-fondo_910766-187.jpg?w=826" alt="Placeholder Image" class="object-cover w-full h-full">
         </div>
         <!-- Right: Login Form -->
         <div class= "lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2">
             <div class="flex justify-center">
                 <img src="<?= option('upload_folder'); ?>logo/logo.png" alt="Placeholder Image" class="object-fit w-52 h-full p-8">
             </div>
                
            <h1 class="text-2xl font-semibold mb-4 text-black">Login</h1>
            <form action="#" method="POST">
               <!-- Username Input -->
               <div class="mb-4 bg-sky-100">
                  <label for="username" class="block text-gray-600">Username</label>
                  <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 text-black" autocomplete="off">
               </div>
               <!-- Password Input -->
               <div class="mb-4">
                  <label for="password" class="block text-gray-800">Password</label>
                  <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 text-black" autocomplete="off">
               </div>
               <!-- Login Button -->
               <button type="submit" class="bg-red-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>
            </form>
         </div>
      </div>
   </body>
</html>