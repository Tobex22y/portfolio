// ==========================================
// LESSON 1: Types, Variables & Functions
// ==========================================

// 1. In standard JavaScript, you write:
// let username = "Alex";
// username = 42; // JS allows this, but it often leads to bugs!

// In TypeScript, we give variables explicit types:
let username: string = "Alex";
let age: number = 25;
let isOnline: boolean = true;

// 2. TypeScript can also INFER types automatically:
let course = "Node.js & TypeScript Mastery"; // TypeScript knows this is string!
// Uncommenting the next line will cause a TypeScript error:
// course = 123; 

// 3. Typed Functions:
// In PHP, you might write: function calculateTotal(float $price, int $quantity): float
// In TypeScript, we write:
function calculateTotal(price: number, quantity: number): number {
    return price * quantity;
}

const total = calculateTotal(19.99, 3);

console.log(`User: ${username} (Age: ${age}, Online: ${isOnline})`);
console.log(`Course: ${course}`);
console.log(`Total Price: $${total.toFixed(2)}`);

// 4. Arrays & Objects
const skills: string[] = ["HTML", "CSS", "JavaScript", "PHP"];
skills.push("TypeScript"); // Valid!
// skills.push(100); // Error: Argument of type 'number' is not assignable to 'string'

console.log(`Skills: ${skills.join(", ")}`);
