from fastapi import FastAPI
from pydantic import BaseModel
import autogen
from autogen import AssistantAgent, UserProxyAgent

app = FastAPI()

class AgentRequest(BaseModel):
    prompt: str
    model: str = None  # Optional: allow user to specify model

# Configure Ollama endpoint and model
ollama_url = "http://localhost:11434"

code_execution_config = {
    "use_docker": False
}

@app.post("/agent")
async def agent_endpoint(request: AgentRequest):
    try:
        # Allow model override from request
        model_to_use = request.model if request.model else "deepseek-coder-v2-lite:latest"
        llm_config = {
            "model": model_to_use,
            "base_url": ollama_url,
            "api_type": "ollama",
            "api_key": None,
        }
        assistant = AssistantAgent(
            name="CodeAssistant",
            llm_config=llm_config,
            system_message=(
                "You are a highly capable coding agent."
                " You can analyze, generate, fix, and test code in any language."
                " When given a prompt, you should:"
                " 1. Analyze the user's request and code context."
                " 2. Proactively suggest improvements, refactoring, or debugging steps."
                " 3. Generate code, explanations, and troubleshooting steps as needed."
                " 4. If the prompt is vague, ask clarifying questions or make reasonable assumptions."
                " 5. Always return a detailed, actionable response."
                " 6. If you detect errors, provide fixes and explanations."
                " 7. If the user requests a plan, break down the solution into clear steps."
            )
        )
        user_proxy = UserProxyAgent(
            name="User",
            human_input_mode="NEVER",
            code_execution_config=code_execution_config
        )
        result = user_proxy.initiate_chat(
            assistant,
            message=request.prompt
        )
        last_message = result.chat_history[-1]["content"] if result.chat_history else ""
        if not last_message or last_message.strip() == "":
            last_message = "Agent executed successfully, but no response was returned. Please try a different prompt or check agent configuration."
        return {"response": last_message}
    except Exception as e:
        import traceback
        return {"error": str(e), "traceback": traceback.format_exc()}
