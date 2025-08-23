import autogen
from autogen import AssistantAgent, UserProxyAgent

# Configure Ollama endpoint and model
ollama_url = "http://localhost:11434"
model_name = "deepseek-coder-v2-lite:latest"  # Change to your preferred model

llm_config = {
    "model": model_name,
    "base_url": ollama_url,
    "api_type": "ollama",
    "api_key": None,  # Not needed for local Ollama
}

# Disable Docker for code execution
code_execution_config = {
    "use_docker": False
}

# Create an assistant agent for coding
assistant = AssistantAgent(
    name="CodeAssistant",
    llm_config=llm_config,
    system_message="You are a helpful coding assistant. You can create, fix, test, and launch code."
)

# Create a user proxy agent
user_proxy = UserProxyAgent(
    name="User",
    human_input_mode="NEVER",
    code_execution_config=code_execution_config
)

# Example task: create a Python function
task = "Write a Python function to sort a list of numbers and provide a test case."

# Patch AutoGen to handle missing token counts gracefully
import autogen.oai.ollama as ollama_mod
def safe_token_count(prompt_tokens, completion_tokens):
    return (prompt_tokens or 0) + (completion_tokens or 0)
ollama_mod.token_count = safe_token_count

# Run the agentic workflow
user_proxy.initiate_chat(
    assistant,
    message=task
)
