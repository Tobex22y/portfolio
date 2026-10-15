# Complete TypeScript & Node.js Syntax Cheat Sheet

## 1. Primitive & Basic Types
```ts
let name: string = "Sarah";
let age: number = 30;           // both integers and floats are 'number'
let isStudent: boolean = true;
let empty: null = null;
let notAssigned: undefined = undefined;
let anyValue: any = "bypass TS checks (avoid!)";
let unknownValue: unknown = "safe alternative to any, requires type checking before use";
```

## 2. Arrays & Tuples
```ts
// Arrays
let numbers: number[] = [1, 2, 3];
let names: Array<string> = ["Alice", "Bob"]; // generic syntax

// Tuples (fixed length & specific types in order)
let userRecord: [number, string, boolean] = [1, "Admin", true];
```

## 3. Objects & Interfaces
```ts
interface User {
  id: number;
  username: string;
  email: string;
  age?: number;               // '?' = optional property
  readonly createdAt: Date;   // 'readonly' = cannot be reassigned
}

const user: User = {
  id: 1,
  username: "johndoe",
  email: "john@example.com",
  createdAt: new Date()
};
```

## 4. Type Aliases, Unions & Literal Types
```ts
// Union types (can be one of several types)
type ID = string | number;

// Literal types (can only be exact values)
type Status = "pending" | "approved" | "rejected";
type HttpMethod = "GET" | "POST" | "PUT" | "DELETE";

let currentStatus: Status = "pending"; // valid
// currentStatus = "finished";         // ERROR: not allowed!

// Intersection (merging multiple types together)
type Timestamps = { createdAt: Date; updatedAt: Date };
type Post = { id: number; title: string } & Timestamps;
```

## 5. Functions & Signatures
```ts
// Named function with param & return types
function add(a: number, b: number): number {
  return a + b;
}

// Arrow function
const multiply = (x: number, y: number): number => x * y;

// Function returning nothing (void)
function logMessage(msg: string): void {
  console.log(msg);
}

// Default and optional parameters
function greet(name: string, greeting: string = "Hello", title?: string): string {
  return `${greeting} ${title ? title + " " : ""}${name}`;
}
```

## 6. Generics (Reusable Type Templates)
```ts
// Generic function that preserves whatever type is passed in
function getFirstItem<T>(arr: T[]): T | undefined {
  return arr[0];
}

const num = getFirstItem([10, 20, 30]);      // Type is number | undefined
const str = getFirstItem(["a", "b", "c"]);  // Type is string | undefined

// Generic API response wrapper
interface ApiResponse<T> {
  status: number;
  message: string;
  data: T;
}

type UserResponse = ApiResponse<User>;
```

## 7. TypeScript Utility Types (Common Built-ins)
```ts
interface Product {
  id: string;
  title: string;
  price: number;
  description: string;
}

// All fields become optional
type UpdateProductInput = Partial<Product>;

// Pick specific fields
type ProductPreview = Pick<Product, "id" | "title">;

// Omit specific fields
type CreateProductInput = Omit<Product, "id">;

// Key-Value dictionary type
type ConfigMap = Record<string, string>;
```

## 8. Modern JavaScript / Node.js Essentials
```ts
// Destructuring
const { id, username } = user;
const [first, second] = numbers;

// Spread operator (cloning / merging)
const updatedUser = { ...user, username: "new_name" };
const combinedList = [...numbers, 4, 5];

// Optional chaining (won't crash if null/undefined)
const avatarUrl = user?.profile?.avatar;

// Nullish coalescing (fallback if null or undefined)
const port = process.env.PORT ?? 3000;
```

## 9. Node.js Asynchronous Syntax (Async/Await)
```ts
import * as fs from "node:fs/promises";
import path from "node:path";

async function readFileExample(fileName: string): Promise<string> {
  try {
    const filePath = path.join(process.cwd(), fileName);
    const content = await fs.readFile(filePath, "utf-8");
    return content;
  } catch (error) {
    console.error("Failed to read file:", error);
    throw error;
  }
}
```

## 10. Express Web Server Syntax
```ts
import express, { Request, Response } from "express";

const app = express();
app.use(express.json()); // parse JSON body

// GET route
app.get("/api/users/:id", (req: Request, res: Response) => {
  const { id } = req.params;
  res.json({ id, name: "Alice" });
});

// POST route
app.post("/api/users", (req: Request, res: Response) => {
  const newUser = req.body;
  res.status(201).json({ success: true, user: newUser });
});

app.listen(3000, () => {
  console.log("Server running on http://localhost:3000");
});
```
