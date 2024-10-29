// Add an event listener to receive messages from the child document
window.addEventListener("message", receiveMessageFromChild, false);

// Define the function to handle received messages
function receiveMessageFromChild(event) {
        // Access the message sent from the child document
        const message = event.data;

        // Handle the message based on its type
        switch (message.type) {
                case "chatbotState":
                        console.log("Received chatbot state:", message.state);
                        // Add your logic to handle the received state
                        const chatbotChat = document.getElementById("chatbot-chat");
                        if (message.state === "opened") {
                                chatbotChat.style.width = "410px"; // Change width when opened
                                chatbotChat.style.height = "700px"; // Change height when opened
                        } else if (message.state === "closed") {
                                chatbotChat.style.width = "85px"; // Change width when closed
                                chatbotChat.style.height = "85px"; // Change height when closed
                        }
                        break;
                // Add more cases if needed for different message types
        }
}
