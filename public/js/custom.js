var terminal = StripeTerminal.create({
    onFetchConnectionToken: fetchConnectionToken,
    onUnexpectedReaderDisconnect: unexpectedDisconnect,
});

// Handler for a "Discover Reader" button
function discoverReaderHandler() {
    var config = { simulated: true };
    terminal.discoverReaders(config).then(function (discoverResult) {
        if (discoverResult.error) {
            console.log('Failed to discover: ', discoverResult.error);
        } else if (discoverResult.discoveredReaders.length === 0) {
            console.log('No available readers.');
        } else {
            discoveredReaders = discoverResult.discoveredReaders;
            log('terminal.discoverReaders', discoveredReaders);
        }
    });
}

// Handler for a "Connect Reader" button
function connectReaderHandler(discoveredReaders) {
    // Just select the first reader here.
    var selectedReader = discoveredReaders[0];
    terminal.connectReader(selectedReader).then(function (connectResult) {
        if (connectResult.error) {
            console.log('Failed to connect: ', connectResult.error);
        } else {
            console.log('Connected to reader: ', connectResult.reader.label);
            log('terminal.connectReader', connectResult)
        }
    });
}