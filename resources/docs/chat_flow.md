# Chat Flow

## Connection init
Client connects to the web-socket server with client token as get parameter `client_token`
   {uri}?client_token={TOKEN}
   At that moment client will be in clients queue until some operator start conversation with this client

## Ping
Every second chat server will send ping-message. Client **must** send pong-message.
```json
    // ping-message:
   {
     type: 'ping',
   }
```

```json
   // pong-message:
   {
     type: 'pong',
   }
```

## Operator init conversation
When Operator initiates connection to the specific client, client will receive message:
```json
   {
     type: 'clientAssigned',
   }
```

## Messages exchange
Then client and operator make message exchanging:
   Client Message example:
   ```json
   {
     type: 'message',
     content: 'blah-blah',
     media: {'url': 'http://example.com', mime: 'image/jpeg'}, // OR null if no media provided
   }
   ```

## Connection close
If operator want to close connection, then client will be disconnected automatically.

If operator unexpectedly close connection, then client will be noticed with message.
In this case, client will be returned to the clients queue.
```json
   {
    type: 'operatorDisconnected',
   }
```